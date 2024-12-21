<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ViewStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only users with the "student" role
        $student = User::role('student')->get();

        // Pass admin data to the view
        return view('students.index', compact('student'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = User::findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $student->roles->pluck('id');
        return view('students.edit', [
            'student' => $student,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id', // Validate roles are valid
        ]);

        // Find the student by ID
        $student = User::findOrFail($id);

        // Update the student details
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->department = $request->input('department');

        // Save the updated student data
        $student->save();

        // Sync roles (this will remove existing roles and add new ones)
        if ($request->has('roles')) {
            $student->syncRoles($request->input('roles'));
        }

        // Redirect with success message
        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the student by ID
        // Find the student by ID
        $student = User::findOrFail($id);

        // Delete the student
        $student->delete();

        // Redirect with success message
        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}