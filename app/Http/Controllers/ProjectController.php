<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\FaceSheetTemplate;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $projects = Project::query()
            ->with('estimations')
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('client', 'like', "%{$search}%");
                });
            })
            ->when($request->input('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'client' => $project->client,
                'location' => $project->location,
                'status' => $project->status,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'total_amount' => $project->total_amount ?? 0,
                'estimations_count' => $project->estimations->count(),
                'created_at' => $project->created_at->toDateTimeString(),
            ]);

        return view('projects.index', [
            'projects' => $projects,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $faceSheetTemplates = FaceSheetTemplate::all()->map(fn ($template) => [
            'id' => $template->id,
            'name' => $template->name,
            'organization_name' => $template->organization_name,
            'division_name' => $template->division_name,
        ]);

        return view('projects.create', [
            'face_sheet_templates' => $faceSheetTemplates,
        ]);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:projects',
            'client' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,on_hold',
            'face_sheet_template_id' => 'nullable|exists:face_sheet_templates,id',
            'sanctioned_estimate_number' => 'nullable|string|max:255',
            'financial_year' => 'nullable|string|max:255',
            'prepared_by' => 'nullable|string|max:255',
            'checked_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = $request->user()->id;

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['estimations' => function ($query) {
            $query->withCount('items')->latest();
        }]);

        return view('projects.show', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'client' => $project->client,
                'location' => $project->location,
                'description' => $project->description,
                'status' => $project->status,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'total_amount' => $project->total_amount ?? 0,
                'sanctioned_estimate_number' => $project->sanctioned_estimate_number,
                'financial_year' => $project->financial_year,
                'prepared_by' => $project->prepared_by,
                'checked_by' => $project->checked_by,
                'approved_by' => $project->approved_by,
                'estimations' => $project->estimations->map(fn ($est) => [
                    'id' => $est->id,
                    'name' => $est->name,
                    'description' => $est->description,
                    'status' => $est->status,
                    'total_amount' => $est->total_amount ?? 0,
                    'items_count' => $est->items_count ?? 0,
                    'created_at' => $est->created_at->toDateTimeString(),
                ]),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $faceSheetTemplates = FaceSheetTemplate::all()->map(fn ($template) => [
            'id' => $template->id,
            'name' => $template->name,
            'organization_name' => $template->organization_name,
            'division_name' => $template->division_name,
        ]);

        return view('projects.edit', [
            'project' => $project,
            'face_sheet_templates' => $faceSheetTemplates,
        ]);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:projects,code,' . $project->id,
            'client' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,on_hold',
            'face_sheet_template_id' => 'nullable|exists:face_sheet_templates,id',
            'sanctioned_estimate_number' => 'nullable|string|max:255',
            'financial_year' => 'nullable|string|max:255',
            'prepared_by' => 'nullable|string|max:255',
            'checked_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
