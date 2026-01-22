<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Estimation;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    /**
     * Show the form for creating a new estimation.
     */
    public function create(Project $project)
    {
        return view('estimations.create', [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
            ],
        ]);
    }

    /**
     * Store a newly created estimation in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate_type' => 'required|in:dsr,ssr,wrd,mixed',
            'royalty_amount' => 'nullable|numeric|min:0',
            'contingency_percentage' => 'nullable|numeric|min:0|max:100',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,final',
        ]);

        $validated['project_id'] = $project->id;
        $validated['user_id'] = $request->user()->id;

        $estimation = Estimation::create($validated);

        return redirect()->route('estimations.edit', $estimation)
            ->with('success', 'Estimation created successfully. Now add items to your estimation.');
    }

    /**
     * Display the specified estimation.
     */
    public function show(Estimation $estimation)
    {
        $estimation->load(['project', 'items']);

        return view('estimations.show', [
            'estimation' => [
                'id' => $estimation->id,
                'name' => $estimation->name,
                'description' => $estimation->description,
                'rate_type' => $estimation->rate_type,
                'status' => $estimation->status,
                'royalty_amount' => $estimation->royalty_amount,
                'contingency_percentage' => $estimation->contingency_percentage,
                'gst_percentage' => $estimation->gst_percentage,
                'sub_total' => $estimation->sub_total,
                'total_amount' => $estimation->total_amount,
                'project' => [
                    'id' => $estimation->project->id,
                    'name' => $estimation->project->name,
                    'code' => $estimation->project->code,
                ],
                'items' => $estimation->items->map(function ($item) {
                    $rateModel = $item->getRateModel();
                    return [
                        'id' => $item->id,
                        'item_code' => $rateModel->item_code ?? 'N.A',
                        'description' => $rateModel->description ?? 'N.A',
                        'unit' => $rateModel->unit ?? 'N.A',
                        'quantity' => $item->quantity,
                        'rate' => $item->rate,
                        'amount' => $item->amount,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified estimation.
     */
    public function edit(Estimation $estimation)
    {
        $estimation->load(['project', 'items.calculationFormula', 'items.measurements']);

        return view('estimations.edit', [
            'estimation' => [
                'id' => $estimation->id,
                'name' => $estimation->name,
                'description' => $estimation->description,
                'rate_type' => $estimation->rate_type,
                'status' => $estimation->status,
                'royalty_amount' => $estimation->royalty_amount,
                'contingency_percentage' => $estimation->contingency_percentage,
                'gst_percentage' => $estimation->gst_percentage,
                'sub_total' => $estimation->sub_total,
                'total_amount' => $estimation->total_amount,
                'project' => [
                    'id' => $estimation->project->id,
                    'name' => $estimation->project->name,
                    'code' => $estimation->project->code,
                ],
                'items' => $estimation->items->map(function ($item) {
                    $rateModel = $item->getRateModel();
                    
                    return [
                        'id' => $item->id,
                        'rate_id' => $item->rate_id,
                        'rate_type' => $item->rate_type,
                        'item_code' => $rateModel->item_code ?? 'N.A',
                        'description' => $rateModel->description ?? 'N.A',
                        'unit' => $rateModel->unit ?? 'N.A',
                        'quantity' => $item->quantity,
                        'rate' => $item->rate,
                        'amount' => $item->amount,
                        'calculation_formula' => $item->calculationFormula ? [
                            'id' => $item->calculationFormula->id,
                            'name' => $item->calculationFormula->name,
                            'code' => $item->calculationFormula->code,
                        ] : null,
                        'calculation_params' => $item->calculation_params,
                        'calculated_quantity' => $item->calculated_quantity,
                        'remarks' => $item->remarks,
                        'sort_order' => $item->sort_order,
                        'measurements_count' => $item->measurements->count(),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the measurement management interface.
     */
    public function manage(Estimation $estimation)
    {
        $estimation->load(['project']);

        return view('estimations.manage', [
            'estimation' => [
                'id' => $estimation->id,
                'name' => $estimation->name,
                'description' => $estimation->description,
                'rate_type' => $estimation->rate_type,
                'status' => $estimation->status,
                'royalty_amount' => $estimation->royalty_amount,
                'contingency_percentage' => $estimation->contingency_percentage,
                'gst_percentage' => $estimation->gst_percentage,
                'sub_total' => $estimation->sub_total,
                'total_amount' => $estimation->total_amount,
                'project' => [
                    'id' => $estimation->project->id,
                    'name' => $estimation->project->name,
                    'code' => $estimation->project->code,
                ],
            ],
        ]);
    }

    /**
     * Update the specified estimation in storage.
     */
    public function update(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate_type' => 'required|in:dsr,ssr,wrd,mixed',
            'royalty_amount' => 'nullable|numeric|min:0',
            'contingency_percentage' => 'nullable|numeric|min:0|max:100',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,final',
        ]);

        $estimation->update($validated);
        $estimation->calculateTotals();

        return redirect()->route('estimations.edit', $estimation)
            ->with('success', 'Estimation updated successfully.');
    }

    /**
     * Remove the specified estimation from storage.
     */
    public function destroy(Estimation $estimation)
    {
        $projectId = $estimation->project_id;
        $estimation->delete();

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Estimation deleted successfully.');
    }

    /**
     * Export estimation to Excel.
     */
    public function export(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'sheets' => 'required|array',
            'sheets.*' => 'string|in:cover,certificate,est,mts,abst,recap,ra,form63'
        ]);

        $exporter = new \App\Services\EstimationExportService();
        $filepath = $exporter->export($estimation, $validated['sheets']);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}
