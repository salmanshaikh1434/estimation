<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use App\Models\EstimationLead;
use App\Models\Material;
use App\Services\LeadService;
use Illuminate\Http\Request;

class EstimationLeadController extends Controller
{
    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Show the lead settings page for an estimation
     */
    public function index(Estimation $estimation)
    {
        $estimation->load(['leads.material', 'project']);
        
        // Get all active materials
        $materials = Material::where('is_active', true)->get();
        
        // Get existing leads or create placeholders
        $leads = [];
        foreach ($materials as $material) {
            $lead = $estimation->leads()->where('material_id', $material->id)->first();
            
            if (!$lead) {
                $lead = new EstimationLead([
                    'estimation_id' => $estimation->id,
                    'material_id' => $material->id,
                    'quarry_location' => '',
                    'lead_distance_km' => 0,
                    'lead_rate_per_km' => 0,
                    'total_lead_charge' => 0,
                ]);
            }
            
            $leads[] = [
                'id' => $lead->id,
                'material_id' => $material->id,
                'material_name' => $material->name,
                'material_unit' => $material->unit,
                'quarry_location' => $lead->quarry_location,
                'lead_distance_km' => $lead->lead_distance_km,
                'lead_rate_per_km' => $lead->lead_rate_per_km,
                'total_lead_charge' => $lead->total_lead_charge,
            ];
        }
        
        return view('estimations.leads', [
            'estimation' => [
                'id' => $estimation->id,
                'name' => $estimation->name,
                'project' => [
                    'id' => $estimation->project->id,
                    'name' => $estimation->project->name,
                    'code' => $estimation->project->code,
                ],
            ],
            'leads' => $leads,
        ]);
    }

    /**
     * Update or create a lead entry
     */
    public function store(Request $request, Estimation $estimation)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quarry_location' => 'nullable|string|max:255',
            'lead_distance_km' => 'required|numeric|min:0',
            'lead_rate_per_km' => 'required|numeric|min:0',
        ]);

        // Find or create lead
        $lead = EstimationLead::updateOrCreate(
            [
                'estimation_id' => $estimation->id,
                'material_id' => $validated['material_id'],
            ],
            $validated
        );

        // Calculate lead charge
        $lead->calculateLeadCharge();
        $lead->save();

        return response()->json([
            'success' => true,
            'lead' => [
                'id' => $lead->id,
                'total_lead_charge' => $lead->total_lead_charge,
            ],
            'message' => 'Lead updated successfully. Rates recalculated.',
        ]);
    }

    /**
     * Get current estimation totals (for real-time preview)
     */
    public function getTotals(Estimation $estimation)
    {
        $estimation->load('items');
        
        return response()->json([
            'sub_total' => $estimation->sub_total,
            'total_amount' => $estimation->total_amount,
            'items_count' => $estimation->items->count(),
        ]);
    }
}
