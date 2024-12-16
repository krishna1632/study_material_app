<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        // dd($user);

        // Pass the user data to the view
        return view('dashboard', compact('user'));//ser dashboard view
    }



    public function update(Request $request, string $id)
    {
        // dd($request->all());
        // Validation
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update user details
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Profile picture update logic
        if ($request->hasFile('profilePic')) {
            $file = $request->file('profilePic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $filename);

            $user->profilePic = 'uploads/profile_pictures/' . $filename;
        }

        // Save user data
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}