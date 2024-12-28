<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubjectController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view subjects', only: ['index']),
            new Middleware('permission:edit subjects', only: ['edit']),
            new Middleware('permission:create subjects', only: ['create']),
            new Middleware('permission:delete subjects', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'subject_type' => 'required|string|in:Core,sec,vac,aec,ge,dse',
            'department' => 'nullable|string|required_if:subject_type,Core|required_if:subject_type,dse',
            'semester' => 'required|integer|min:1|max:8',
            'subject_name' => 'required|string|max:255',
        ]);

        // Set department to null if not required
        $validatedData['department'] = $validatedData['department'] ?? null;

        // Check for duplicate entry
        $duplicate = Subject::where('subject_type', $validatedData['subject_type'])
            ->where('semester', $validatedData['semester'])
            ->where(function ($query) use ($validatedData) {
                $query->where('department', $validatedData['department'])
                      ->orWhereNull('department');
            })
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'This subject already exists for the selected semester and department.');
        }

        // Create the subject
        Subject::create($validatedData);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully!');
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
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'subject_type' => 'required|string|in:Core,sec,vac,aec,ge,dse',
            'department' => 'nullable|string|required_if:subject_type,Core|required_if:subject_type,dse',
            'semester' => 'required|integer|min:1|max:8',
            'subject_name' => 'required|string|max:255',
        ]);

        $validatedData['department'] = $validatedData['department'] ?? null;

        $subject = Subject::findOrFail($id);

        // Check for duplicate entry excluding the current record
        $duplicate = Subject::where('subject_type', $validatedData['subject_type'])
            ->where('semester', $validatedData['semester'])
            ->where(function ($query) use ($validatedData) {
                $query->where('department', $validatedData['department'])
                      ->orWhereNull('department');
            })
            ->where('id', '!=', $subject->id)
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'This subject already exists for the selected semester and department.');
        }

        $subject->update($validatedData);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully!');
    }
}
