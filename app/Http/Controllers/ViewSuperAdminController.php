<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ViewSuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only users with the "Admin" role
        $super = User::role('SuperAdmin')->get();

        // Pass admin data to the view
        return view('superadminView.index', compact('super'));
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
        $super = User::findOrFail($id);
        return view('superadminView.show', compact('super'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the faculty user by ID
        $super = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $super->roles->pluck('id');
        return view('superadminView.edit', [
            'super' => $super,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Fetch the SuperAdmin user by ID
        $super = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'department' => 'required|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Update SuperAdmin details
        $super->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        // Sync roles if provided
        if ($request->has('roles')) {
            $super->syncRoles($request->roles);
        }

        // Redirect with success message
        return redirect()->route('superadminView.index')->with('success', 'SuperAdmin updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the SuperAdmin user by ID
        $super = User::findOrFail($id);

        // Check if the user has the "SuperAdmin" role
        if (!$super->hasRole('SuperAdmin')) {
            return redirect()->route('superadminView.index')->with('error', 'User does not have the SuperAdmin role.');
        }

        // Delete the user
        $super->delete();

        // Redirect with a success message
        return redirect()->route('superadminView.index')->with('success', 'SuperAdmin deleted successfully!');
    }
}