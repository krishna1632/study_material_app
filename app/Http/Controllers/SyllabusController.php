<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the syllabus.
     */
    public function index()
    {
        $user = auth()->user();
        $department = $user->department;

        if ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
            $syllabus = Syllabus::all();
        } else {
            $syllabus = Syllabus::where('department', $department)->get();
        }

        return view('syllabus.index', compact('syllabus'));
    }

    /**
     * Show the form for creating a new syllabus.
     */
    public function create()
    {
        $user = auth()->user();
        $roles = $user->getRoleNames();

        if ($roles->contains('Admin') || $roles->contains('SuperAdmin')) {
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
            $departments = [$user->department];
        }

        $subjectTypes = ['Core', 'SEC', 'GE', 'VAC', 'DSE', 'AEC'];
        $semesters = ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6'];

        return view('syllabus.create', compact('departments', 'subjectTypes', 'semesters'));
    }

    public function getSubjects(Request $request)
    {
        $subjectType = $request->input('subject_type');
        $department = $request->input('department');
        $semester = $request->input('semester');
    
        // Ensure subject type, department, and semester are provided
        if (!$subjectType || !$department || !$semester) {
            return response()->json(['error' => 'Please select all fields.'], 400);
        }
    
        // Hardcoded data for subjects
        $subjectsData = [
            'Applied Psychology' => [
                'Semester 1' => [
                    'Core' => ['Introduction to Psychology', 'Biological Basis of Behaviour'],
                    'GE' => ['Positive Psychology', 'Psychology in Everyday Life']
                ],
                'Semester 2' => [
                    'Core' => ['Cognitive Psychology', 'Social Psychology'],
                    'GE' => ['Human Strengths', 'Organizational Behaviour']
                ]
            ],
            'Computer Science' => [
                'Semester 1' => [
                    'Core' => ['Programming in C', 'Discrete Mathematics'],
                    'GE' => ['Basics of Computers', 'Introduction to Algorithms']
                ],
                'Semester 2' => [
                    'Core' => ['Data Structures', 'Digital Logic'],
                    'GE' => ['Computer Networks', 'Web Development Basics']
                ]
            ],
        ];
    
        // Fetch subjects based on input
        $subjects = $subjectsData[$department][$semester][$subjectType] ?? [];
    
        // Check if any subjects are found
        if (empty($subjects)) {
            return response()->json(['message' => 'No subjects found for the selected criteria.'], 404);
        }
    
        // Return the list of subjects as JSON
        return response()->json(['subjects' => $subjects]);
    }
    
    
    /**
     * Store a newly created syllabus in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'required|string|max:255',
            'department' => 'required_if:subject_type,Core|string|max:255',
            'semester' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = $request->file('file')->store('syllabus', 'public');

        Syllabus::create([
            'subject_type' => $validated['subject_type'],
            'department' => $validated['department'] ?? null,
            'semester' => $validated['semester'],
            'name' => $validated['name'],
            'file_path' => $filePath,
        ]);

        return redirect()->route('syllabus.index')->with('success', 'Syllabus uploaded successfully!');
    }

    /**
     * Show the specified syllabus.
     */
    public function show($id)
    {
        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return redirect()->route('syllabus.index')->with('error', 'Syllabus not found!');
        }

        return view('syllabus.show', compact('syllabus'));
    }

    /**
     * Show the form for editing the specified syllabus.
     */
    public function edit($id)
    {
        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return redirect()->route('syllabus.index')->with('error', 'Syllabus not found!');
        }

        $user = auth()->user();
        $roles = $user->getRoleNames();

        if ($roles->contains('Admin') || $roles->contains('SuperAdmin')) {
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
            $departments = [$user->department];
        }

        $subjectTypes = ['Core', 'SEC', 'GE', 'VAC', 'DSE', 'AEC'];
        $semesters = ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6'];

        return view('syllabus.edit', compact('syllabus', 'departments', 'subjectTypes', 'semesters'));
    }

    /**
     * Update the specified syllabus in storage.
     */
    public function update(Request $request, $id)
    {
        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return redirect()->route('syllabus.index')->with('error', 'Syllabus not found!');
        }

        $validated = $request->validate([
            'subject_type' => 'required|string|max:255',
            'department' => 'required_if:subject_type,Core|string|max:255',
            'semester' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if (Storage::exists('public/' . $syllabus->file_path)) {
                Storage::delete('public/' . $syllabus->file_path);
            }

            $filePath = $request->file('file')->store('syllabus', 'public');
            $syllabus->file_path = $filePath;
        }

        $syllabus->update([
            'subject_type' => $validated['subject_type'],
            'department' => $validated['department'] ?? null,
            'semester' => $validated['semester'],
            'name' => $validated['name'],
            'file_path' => $syllabus->file_path,
        ]);

        return redirect()->route('syllabus.index')->with('success', 'Syllabus updated successfully!');
    }

    /**
     * Remove the specified syllabus from storage.
     */
    public function destroy($id)
    {
        $syllabus = Syllabus::find($id);

        if (!$syllabus) {
            return redirect()->route('syllabus.index')->with('error', 'Syllabus not found!');
        }

        if (Storage::exists('public/' . $syllabus->file_path)) {
            Storage::delete('public/' . $syllabus->file_path);
        }

        $syllabus->delete();

        return redirect()->route('syllabus.index')->with('success', 'Syllabus deleted successfully!');
    }
}
