<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PdfSummaryController extends Controller
{
    public function index()
    {
        return view('summarize.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf'    => 'nullable|file|mimes:pdf|max:20480', // 20MB
            'doc_id' => 'nullable|string',
            'query'  => 'nullable|string|max:500',
        ]);

        $query = $request->input('query', 'summarize this document');

        // If a doc_id is provided (pre-ingested by teacher), query RAG directly
        if ($request->filled('doc_id') && !$request->hasFile('pdf')) {
            $docId = $request->input('doc_id');
            $response = Http::timeout(600)
                ->post(config('services.rag.url') . '/query', [
                    'doc_id' => $docId,
                    'query'  => $query,
                ]);

            if ($response->failed()) {
                $errorMessage = $response->json('error') ?? $response->body() ?? 'Processing failed. Is the RAG service running?';
                return response()->json(['error' => $errorMessage], 502);
            }

            return response()->json($response->json());
        }

        // Otherwise expect a PDF upload from student
        if (!$request->hasFile('pdf')) {
            return response()->json(['error' => 'No PDF or doc_id provided'], 422);
        }

        $file   = $request->file('pdf');
        $docId  = Str::uuid()->toString();

        $response = Http::timeout(600)
            ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post(config('services.rag.url') . '/process', [
                'doc_id' => $docId,
                'query'  => $query,
            ]);

        if ($response->failed()) {
            $errorMessage = $response->json('error') ?? $response->body() ?? 'Processing failed. Is the RAG service running?';
            return response()->json(['error' => $errorMessage], 502);
        }

        return response()->json($response->json());
    }
}
