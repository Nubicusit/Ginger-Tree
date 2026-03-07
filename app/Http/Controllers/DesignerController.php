<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignerController extends Controller
{

    public function dashboard()
    {
        return view('designer.dashboard');
    }

   public function index()
{
    return view('designer.dashboard');
}

    public function show($id)
    {
        return view('design-status.show');
    }

    /**
     * Show form to create a new design project.
     */
    public function create()
    {
        return view('design-status.create');
    }

    /**
     * Store a new design project.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'designer' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'status'   => 'required|in:pending,in_progress,completed',
        ]);

        // DesignProject::create($request->all());

        return redirect()->route('design-status.index')
                         ->with('success', 'Design project created successfully.');
    }

    /**
     * Show form to edit a design project.
     */
    public function edit($id)
    {
        return view('design-status.edit');
    }

    /**
     * Update a design project.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'designer' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'status'   => 'required|in:pending,in_progress,completed',
        ]);

        // $project = DesignProject::findOrFail($id);
        // $project->update($request->all());

        return redirect()->route('design-status.index')
                         ->with('success', 'Design project updated successfully.');
    }

    /**
     * Delete a design project.
     */
    public function destroy($id)
    {
        // $project = DesignProject::findOrFail($id);
        // $project->delete();

        return redirect()->route('design-status.index')
                         ->with('success', 'Design project deleted successfully.');
    }
}
