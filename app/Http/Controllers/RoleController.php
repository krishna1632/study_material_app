<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit']),
            new Middleware('permission:create roles', only: ['create']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(5);
        return view('roles.list', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();

        return view('roles.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {


            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permissions)) {
                foreach ($request->permissions as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Roles Addad successfully');


        } else {

            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the role by ID
        $role = Role::findOrFail($id);

        // Fetch the permissions directly from the role's relationship
        $hasPermissions = $role->permissions->pluck('name');

        // dd($hasPermissions);

        // Fetch all available permissions to show in the edit form
        $permissions = Permission::orderBy('name', 'ASC')->get();

        // Pass the data to the edit view
        return view('roles.edit', [

            'role' => $role,
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        // Fetch the role
        $role = Role::findOrFail($id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . ',id', // Exclude current role name from unique check
        ]);

        if ($validator->passes()) {
            // Update the role's name
            $role->name = $request->name;
            $role->save();

            // Check if permissions are provided
            if (!empty($request->permissions)) {
                // Sync permissions (permissions array is provided)
                $role->syncPermissions($request->permissions);
            } else {
                // If no permissions are selected, remove all permissions
                $role->syncPermissions([]);
            }

            // Redirect back to roles index with success message
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } else {
            // Redirect back to the form with validation errors
            return redirect()->route('roles.edit', $id)->withInput()->withErrors($validator);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Fetch the role by ID
        $role = Role::findOrFail($id);

        try {
            // Delete the role
            $role->delete();

            // Redirect back to roles index with success message
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            // Handle any errors during deletion
            return redirect()->route('roles.index')->with('error', 'Error deleting the role: ' . $e->getMessage());
        }
    }

}