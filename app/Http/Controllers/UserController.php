<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{


    public static function middleware()
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:edit users', only: ['edit']),
            new Middleware('permission:create users', only: ['create']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::oldest()->paginate(10);
        return view('users.list', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get(); // Saare roles fetch kar rahe hain
        return view('auth.register', compact('roles')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|unique:users,phone|max:15', // Max length added for phone
        'department' => 'required|string|max:255', // Removed unique constraint if department repeats
         'semester' => 'required|integer|min:1|max:10',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|exists:roles,name', // Role validation
    ]);

    // Create User
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone; // Save phone
    $user->department = $request->department; // Save department
    $user->semester = $request->semester;
    $user->password = bcrypt($request->password);
    $user->save();

    // Assign role to the user
    $user->assignRole($request->role);

    // Redirect with success message
    return redirect()->route('users.list')->with('success', 'User created successfully!');
}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('id');
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|min:3|max:255',
        'email' => 'required|email|unique:users,email,' . $id . ',id',
        'phone' => 'required|string|max:15|unique:users,phone,' . $id . ',id',
        'department' => 'required|string|max:255',
        'semester' => $user->hasRole('student') ? 'required|integer|min:1|max:10' : 'nullable',
        'role' => 'nullable|array',
        'role.*' => 'exists:roles,name',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'semester' => $user->hasRole('student') ? $request->semester : null,
        ]);

        $user->syncRoles($request->role ?? []);

        return redirect()->route('users.list')->with('success', 'User updated successfully!');
    } catch (\Exception $e) {
        \Log::error('User Update Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
    }
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        try {
            // Delete the user
            $user->delete();

            // Redirect back with success message
            return redirect()->route('users.list')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message if deletion fails
            return redirect()->route('users.list')->with('error', 'Failed to delete the user. Please try again later.');
        }
    }
}