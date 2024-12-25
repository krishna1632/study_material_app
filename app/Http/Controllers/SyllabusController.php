<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); // Currently logged-in user

        // Agar user ka role `student` hai
        if ($user->hasRole('student')) {
            // Core syllabus sirf uske department ka dikhaye
            $syllabus = Syllabus::where(function ($query) use ($user) {
                $query->where('subject_type', 'Core')
                    ->where('department', $user->department);
            })->orWhere(function ($query) {
                // Elective syllabus ke liye koi restriction nahi
                $query->whereIn('subject_type', ['SEC', 'VAC', 'GE', 'AEC', 'DSE']);
            })->get();
        } else {
            // Agar user ka role student nahi hai, to sab syllabus dikhaye
            $syllabus = Syllabus::all();
        }

        return view('syllabus.index', compact('syllabus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjectTypes = [
            'Core' => ['BMS', 'B.Voc Software Development', 'B.com Hons'],
            'SEC' => ['Frontend', 'Analytics with Python', 'Blockchain'],
            'VAC' => ['Vedic Maths 1', 'Vedic Maths 2', 'Digital Empowerment'],
            'GE' => ['Maths', 'CS', 'Management'],
            'AEC' => ['EVS 1', 'Hindi-C', 'EVS 2'],
            'DSE' => ['DIP', 'Big Data'],
        ];

        return view('syllabus.create', compact('subjectTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'subject_type' => 'required|string|max:255',
            'department' => 'required|string|max:255', // Ensure 'department' is required
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max size 10MB
        ]);

        // Store the uploaded file
        $filePath = $request->file('file')->store('syllabus_files', 'public'); // 'syllabus_files' folder in 'storage/app/public'

        // Create a new syllabus entry in the database
        Syllabus::create([
            'subject_type' => $request->subject_type,
            'department' => $request->name, // Ensure department is stored
            'file_path' => $filePath, // Store the file path in the database
            'is_visible' => true, // Default visibility
        ]);

        // Redirect back to the syllabus index page with success message
        return redirect()->route('syllabus.index')->with('success', 'Syllabus uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Syllabus $syllabus)
    {
        return view('syllabus.show', compact('syllabus'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Syllabus $syllabus)
    {
        return view('syllabus.edit', compact('syllabus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Syllabus $syllabus)
    {
        $request->validate([
            'subject_type' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // File validation
        ]);

        // Find the syllabus
        $syllabus->subject_type = $request->subject_type;
        $syllabus->department = $request->department;

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete the old file if it exists
            if ($syllabus->file) {
                Storage::delete('public/' . $syllabus->file);
            }

            // Upload the new file
            $file = time() . '_' . $request->file->getClientOriginalName();
            $request->file->storeAs('public/syllabus', $file); // Store in storage/app/public/yllabus
            $syllabus->file = 'syllabus/' . $file; // Save relative path for public access
        }

        $syllabus->save();

        return redirect()->route('syllabus.index')->with('success', 'Syllabus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Syllabus $syllabus)
    {
        if ($syllabus->file) {
            Storage::delete('public/' . $syllabus->file);
        }

        // Delete the study material from the database
        $syllabus->delete();

        return redirect()->route('syllabus.index')->with('success', 'Syllabus deleted successfully.');
    }
}