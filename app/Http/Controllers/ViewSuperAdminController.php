<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ViewSuperAdminController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view superadmins', only: ['index']),
            new Middleware('permission:edit superadmins', only: ['edit']),
            new Middleware('permission:delete superadmins', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch only users with the "SuperAdmin" role
        $super = User::role('SuperAdmin')->get();

        // Encrypt the IDs before passing to the view
        $super->transform(function ($item) {
            $item->encrypted_id = Crypt::encryptString($item->id);
            return $item;
        });

        // Pass data to the view
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
        // Decrypt the ID
        $decryptedId = Crypt::decryptString($id);

        // Find the SuperAdmin user
        $super = User::findOrFail($decryptedId);

        // Pass data to the view
        return view('superadminView.show', compact('super'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Decrypt the ID
        $decryptedId = Crypt::decryptString($id);

        // Find the SuperAdmin user
        $super = User::findOrFail($decryptedId);

        // Get roles and current roles of the SuperAdmin
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $super->roles->pluck('id');

        // Pass data to the view
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
        // Decrypt the ID
        $decryptedId = Crypt::decryptString($id);

        // Find the SuperAdmin user
        $super = User::findOrFail($decryptedId);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $decryptedId,
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
        if ($request->has('role')) {
            $roles = $request->input('role'); // Array of role names
            $super->syncRoles($roles); // Sync roles for the superadmin
        } else {
            // If no roles are selected, remove all roles
            $super->syncRoles([]);
        }

        // Redirect with success message
        return redirect()->route('superadminView.index')->with('success', 'SuperAdmin updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Decrypt the ID
        $decryptedId = Crypt::decryptString($id);

        // Find the SuperAdmin user
        $super = User::findOrFail($decryptedId);

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
