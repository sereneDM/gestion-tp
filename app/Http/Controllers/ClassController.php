<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    // View all classes (admin supervision)
    public function index()
    {
        $classes = ClassModel::with(['teacher', 'students'])
                             ->withCount('students')
                             ->orderBy('status')
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        return view('admin.classes.index', compact('classes'));
    }

    // Show class details (view only)
    public function show($id)
    {
        $class = ClassModel::with(['teacher', 'students'])
                           ->findOrFail($id);
        
        return view('admin.classes.show', compact('class'));
    }

    // Delete a class (admin only - for supervision)
    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')
                         ->with('success', 'Classe supprimée avec succès!');
    }
}