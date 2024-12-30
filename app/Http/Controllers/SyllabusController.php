<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Syllabus;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SyllabusController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view syllabus', only: ['index']),
            new Middleware('permission:create syllabus', only: ['create']),
            new Middleware('permission:edit syllabus', only: ['edit']),
            new Middleware('permission:delete syllabus', only: ['destroy']),
        ];
    }

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

    $subjectTypes = ['CORE', 'DSE', 'SEC', 'VAC', 'GE', 'AEC'];
    $departments = $roles->contains('Admin') || $roles->contains('SuperAdmin')
        ? [
            'Applied Psychology', 'Computer Science', 'B.voc(Software Development)', 'Economics', 'English', 'Environmental Studies', 'Commerce',
            'Punjabi', 'Hindi', 'History', 'Management Studies', 'Mathematics',
            'Philosophy', 'Physical Education', 'Political Science', 'Statistics',
            'B.voc(Banking Operations)','ELECTIVE'
        ]
        : [$user->department];
    $semesters = [1, 2, 3, 4, 5, 6]; // Example semesters
    
    // Initial empty subject list
    $subjects = [];

    return view('syllabus.create', compact('subjectTypes', 'departments', 'semesters', 'subjects'));
}



public function filterSubjects(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'subject_type' => 'required|string',
        'department' => 'required|string',
        'semester' => 'required|integer',
    ]);

    // Initialize the query builder for the Subject model
    $query = Subject::query();

    // If the subject type is either 'CORE' or 'DSE', filter by department and semester
    if (in_array($validated['subject_type'], ['CORE', 'DSE'])) {
        $query->where('subject_type', $validated['subject_type'])
              ->where('department', $validated['department'])
              ->where('semester', $validated['semester']);
    } else {
        // If the subject type is 'SEC', 'VAC', 'GE', or 'AEC', only filter by semester
        $query->where('subject_type', $validated['subject_type'])
              ->where('semester', $validated['semester']);
    }

    // Fetch the subjects based on the filters
    $subjects = $query->get();

    // Return subjects as JSON response with id and name
    return response()->json($subjects->pluck('subject_name', 'id'));
}

    /**
     * Store a newly created syllabus in storage.
     */
    public function store(Request $request)
{
    // Validate the input
    $request->validate([
        'subject_type' => 'required',
        'department' => 'nullable|string',
        'semester' => 'required|integer',
        'subject_name' => 'required|string',
        'file' => 'required|file|mimes:pdf,doc,docx',
    ]);


    // Department will default to 'ELECTIVE' if not provided
    $department = $request->department ?? 'ELECTIVE';
    // Handle file upload and get the path
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('syllabus', 'public');
    } else {
        // Manually throw an error if file is not provided
        return redirect()->back()->with('error', 'File is required!');
    }

    // Create the syllabus record
    Syllabus::create([
        'subject_type' => $request->subject_type,
        'department' => $request->department,
        'semester' => $request->semester,
        'subject_name' => $request->subject_name,
        'file' => $filePath ?? null,
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
                'B.voc(Banking Operations)',
                'ELECTIVE'
            ];
        } else {
            $departments = [$user->department];
        }

        $subjectTypes = ['CORE', 'SEC', 'GE', 'VAC', 'DSE', 'AEC'];
        $semesters = ['1', '2', '3', '4', '5', '6'];

        // Fetch subjects based on the current syllabus data
        $subjects = Subject::where('subject_type', $syllabus->subject_type)
            ->where('department', $syllabus->department)
            ->where('semester', $syllabus->semester)
            ->pluck('subject_name', 'id'); // Fetch subjects as id => name pairs

        return view('syllabus.edit', compact('syllabus', 'departments', 'subjectTypes', 'semesters', 'subjects'));
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
            'subject_name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);


        $department = $request->department ?? 'Elective';
        if ($request->hasFile('file')) {
            if (Storage::exists('public/' . $syllabus->file)) {
                Storage::delete('public/' . $syllabus->file);
            }

            $filePath = $request->file('file')->store('syllabus', 'public');
            $syllabus->file = $filePath;
        }

        $syllabus->update([
            'subject_type' => $validated['subject_type'],
            'department' => $validated['department'] ?? null,
            'semester' => $validated['semester'],
            'subject_name' => $validated['subject_name'],
            'file' => $syllabus->file,
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
