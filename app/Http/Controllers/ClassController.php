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

    // Show create form
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        return view('admin.classes.create', compact('teachers', 'students'));
    }

    // Store new class
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $class = ClassModel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'teacher_id' => $validated['teacher_id'],
            'status' => 'active',
            'join_code' => strtoupper(bin2hex(random_bytes(3))), // Generate random code
        ]);

        if (isset($validated['students'])) {
            $class->students()->attach($validated['students']);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Classe créée avec succès!');
    }

    // Show edit form
    public function edit($id)
    {
        $class = ClassModel::with('students')->findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        return view('admin.classes.edit', compact('class', 'teachers', 'students'));
    }

    // Update class
    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
        ]);

        $class->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'teacher_id' => $validated['teacher_id'],
        ]);

        if (isset($validated['students'])) {
            $class->students()->sync($validated['students']);
        } else {
            $class->students()->detach();
        }

        return redirect()->route('admin.classes.index')->with('success', 'Classe mise à jour avec succès!');
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