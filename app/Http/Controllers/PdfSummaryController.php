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
        // Increase PHP execution time for long-running AI operations
        set_time_limit(180); // 3 minutes
        
        // Handle JSON requests (Content-Type: application/json)
        $data = $request->all();
        if ($request->header('Content-Type') === 'application/json' && empty($data)) {
            $jsonData = json_decode($request->getContent(), true);
            if (is_array($jsonData)) {
                $data = $jsonData;
            }
        }

        // Validate the incoming data
        $request->validate([
            'pdf'    => 'nullable|file|mimes:pdf|max:20480', // 20MB
            'doc_id' => 'nullable|string',
            'query'  => 'nullable|string|max:500',
        ]);

        $query = $data['query'] ?? $request->input('query', 'summarize this document');

        // Flow: Using existing doc_id (prioritized over file upload)
        if ((isset($data['doc_id']) && !empty($data['doc_id'])) || $request->filled('doc_id')) {
            $docId = $data['doc_id'] ?? $request->input('doc_id');
            $response = Http::timeout(150)
                ->asJson()
                ->post(config('services.rag.url') . '/query', [
                    'doc_id' => $docId,
                    'query'  => $query,
                ]);

            if ($response->status() === 404) {
                $course = \App\Models\ClassModel::where('course_doc_id', $docId)->first();
                if ($course && $course->course_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->course_pdf)) {
                    $fileContent = \Illuminate\Support\Facades\Storage::disk('public')->get($course->course_pdf);
                    $response = Http::timeout(150)
                        ->attach('file', $fileContent, basename($course->course_pdf))
                        ->post(config('services.rag.url') . '/process', [
                            'doc_id' => $docId,
                            'query'  => $query,
                        ]);
                }
            }
            return $this->handleRagResponse($response);
        }

        // Flow: Direct PDF upload
        if (!$request->hasFile('pdf')) {
            return response()->json(['error' => 'No PDF or doc_id provided'], 422);
        }

        $file = $request->file('pdf');
        $response = Http::timeout(150)
            ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
            ->post(config('services.rag.url') . '/process', [
                'doc_id' => \Illuminate\Support\Str::uuid()->toString(),
                'query'  => $query,
            ]);

        return $this->handleRagResponse($response);
    }

    /**
     * Robustly handle RAG response and extract JSON from potential markdown/junk
     */
    private function handleRagResponse($response)
    {
        if ($response->failed()) {
            $errorMessage = $response->json('error') ?? $response->body() ?? 'Processing failed.';
            return response()->json(['error' => $errorMessage], 502);
        }

        $responseData = $response->json();
        if (isset($responseData['result'])) {
            $result = $responseData['result'];
            
            // Try direct decode first
            $decoded = json_decode($result, true);
            
            // If failed, try to strip markdown code blocks and extract the JSON object
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                // Extract anything between the first { and last }
                if (preg_match('/({.*})/s', $result, $matches)) {
                    $cleaned = $matches[1];
                    // Strip potential markdown markers
                    $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($cleaned));
                    $decoded = json_decode($cleaned, true);
                }
            }

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return response()->json(array_merge($responseData, $decoded));
            }
        }

        return response()->json($responseData);
    }

}
