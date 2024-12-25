<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roadmaps;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoadmapsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view roadmaps', only: ['index']),
            new Middleware('permission:create roadmaps', only: ['create']),
            new Middleware('permission:edit roadmaps', only: ['edit']),
            new Middleware('permission:delete roadmaps', only: ['destroy']),
        ];
    }


    /**
     * Display a listing of the roadmaps.
     */
    public function index()
    {
        $department = auth()->user()->department; // User ka department
        $user = auth()->user(); // Currently logged-in user

        // Agar user ka role 'SuperAdmin' ya 'Admin' hai, to saare roadmaps dikhayenge
        if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin')) {
            $roadmaps = Roadmaps::all(); // Saare roadmaps
        } else {
            // Agar role kuch aur ho, to department-wise filter karein
            $roadmaps = Roadmaps::where('department', $department)->get();
        }




        return view('roadmaps.index', compact('roadmaps'));
    }

    /**
     * Show the form for creating a new roadmap.
     */



    public function create()
    {
        $user = auth()->user(); // Currently logged-in user
        $roles = $user->getRoleNames(); // Fetch assigned roles for the user

        if ($roles->contains('Admin') || $roles->contains('SuperAdmin')) {
            // If the user has Admin or SuperAdmin role, show all departments
            $departments = [
                'Applied Psychology',
                'Computer Science',
                'B.voc(Software Development)',
                'Economics',
                'English',
                'Environmental Studies',
                'Commerce',
                'Punjabi',
                'Hindi',
                'History',
                'Management Studies',
                'Mathematics',
                'Philosophy',
                'Physical Education',
                'Political Science',
                'Statistics',
                'B.voc(Software Banking)',
            ];
        } else {
            // If the user has other roles, show only their department
            $departments = [$user->department];
        }

        return view('roadmaps.create', compact('departments'));
    }





    /**
     * Store a newly created roadmap in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'department' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Allow specific file types
            'description' => 'required|string',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('roadmaps', 'public'); // Store file in storage/app/public/roadmaps
        }

        // Create a new roadmap
        Roadmaps::create([
            'department' => $validated['department'],
            'title' => $validated['title'],
            'file' => $filePath ?? null,
            'description' => $validated['description'],
        ]);

        return redirect()->route('roadmaps.index')->with('success', 'Roadmap added successfully!');
    }



    /**
     * Show the specified roadmap.
     */
    public function show(Roadmaps $roadmap, $id)
    {
        $roadmap = Roadmaps::find($id);
        // dd($roadmap);
        return view('roadmaps.show', compact('roadmap'));
    }


    public function edit(Roadmaps $roadmap, $id)
    {
        $roadmap = Roadmaps::find($id);
        return view('roadmaps.edit', compact('roadmap'));
    }

    public function update(Request $request, Roadmaps $roadmap, $id)
    {

        $roadmap = Roadmaps::find($id);
        // Validate the request
        $validated = $request->validate([
            'department' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Optional file upload
            'description' => 'required|string',
        ]);

        // Handle file upload (if new file is uploaded)
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($roadmap->file && Storage::exists('public/' . $roadmap->file)) {
                Storage::delete('public/' . $roadmap->file);
            }

            // Store the new file
            $filePath = $request->file('file')->store('roadmaps', 'public');
            $roadmap->file = $filePath;
        }

        // Update the roadmap record
        $roadmap->update([
            'department' => $validated['department'],
            'title' => $validated['title'],
            'file' => $roadmap->file ?? null, // Keep the old file if no new file is uploaded
            'description' => $validated['description'],
        ]);

        return redirect()->route('roadmaps.index')->with('success', 'Roadmap updated successfully!');
    }



    public function destroy($id)
    {
        // Find the roadmap by ID
        $roadmap = Roadmaps::find($id);

        // Check if roadmap exists
        if (!$roadmap) {
            return redirect()->route('roadmaps.index')->with('error', 'Roadmap not found!');
        }

        // Delete the roadmap record
        $roadmap->delete();

        // Redirect back with success message
        return redirect()->route('roadmaps.index')->with('success', 'Roadmap deleted successfully!');
    }

}