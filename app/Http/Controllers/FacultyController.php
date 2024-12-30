<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FacultyController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view faculties', only: ['index']),
            new Middleware('permission:edit faculties', only: ['edit']),
            new Middleware('permission:delete faculties', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only users with the "Faculty" role
        $faculties = User::role('Faculty')->get();

        // Encrypt the IDs
        foreach ($faculties as $faculty) {
            $faculty->encrypted_id = Crypt::encryptString($faculty->id);
        }

        // Pass faculty data to the view
        return view('faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'department' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Store the faculty as a user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('faculties.index')->with('success', 'Faculty added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);
        $faculty = User::findOrFail($id);
        return view('faculties.show', compact('faculty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);
        $faculty = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $faculty->roles->pluck('id');
        return view('faculties.edit', [
            'faculty' => $faculty,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);

        // Fetch the faculty user by ID
        $faculty = User::findOrFail($id);

        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'department' => 'required|string|max:255',
        ]);

        // Update the faculty details
        $faculty->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        // Sync roles if provided
        if ($request->has('role')) {
            $roles = $request->input('role'); // Array of role names
            $faculty->syncRoles($roles); // Sync roles for the faculty
        } else {
            // If no roles are selected, remove all roles
            $faculty->syncRoles([]);
        }

        // Redirect to the faculty index page with a success message
        return redirect()->route('faculties.index')->with('success', 'Faculty updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encrypted_id)
    {
        $id = Crypt::decryptString($encrypted_id);
        $faculty = User::findOrFail($id);
        $faculty->delete();

        return redirect()->route('faculties.index')->with('success', 'Faculty deleted successfully!');
    }
}
