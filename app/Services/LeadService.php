<?php

namespace App\Services;

use App\Models\Estimation;
use App\Models\EstimationLead;
use App\Models\ItemMaterialConsumption;

class LeadService
{
    /**
     * Update all item rates for an estimation after lead changes
     * 
     * @param Estimation $estimation
     * @return void
     */
    public function updateEstimationRates(Estimation $estimation): void
    {
        // Reload estimation with all relationships
        $estimation->load(['items.measurements']);
        
        foreach ($estimation->items as $item) {
            $this->calculateItemRate($item);
            $item->calculateAmount(); // Recalculate total amount
        }
        
        // Recalculate estimation totals
        $estimation->calculateTotals();
    }

    /**
     * Calculate the rate for a specific estimation item
     * Formula: Basic Rate + Lead Charges + Local Adjustments
     * 
     * @param \App\Models\EstimationItem $item
     * @return float
     */
    public function calculateItemRate($item): float
    {
        // Get the basic rate from SSR/DSR/WRD
        $rateModel = $item->getRateModel();
        $basicRate = $rateModel->rate ?? 0;
        
        // Calculate lead charges for all materials used in this item
        $leadCharges = $this->calculateLeadCharges($item);
        
        // Get local tax percentage (e.g., Municipal Council 4%)
        $localTaxPercent = $item->estimation->contingency_percentage ?? 0;
        
        // Calculate final rate
        $subtotal = $basicRate + $leadCharges;
        $finalRate = $subtotal * (1 + $localTaxPercent / 100);
        
        // Update and save the item
        $item->rate = round($finalRate, 2);
        $item->save();
        
        return $item->rate;
    }

    /**
     * Calculate total lead charges for an item based on material consumption
     * 
     * @param \App\Models\EstimationItem $item
     * @return float
     */
    protected function calculateLeadCharges($item): float
    {
        $totalLeadCharge = 0;
        
        // Get material consumption for this SSR rate
        $consumptions = ItemMaterialConsumption::where('ssr_rate_id', $item->rate_id)
            ->with('material')
            ->get();
        
        foreach ($consumptions as $consumption) {
            // Get the lead for this material in this estimation
            $lead = EstimationLead::where('estimation_id', $item->estimation_id)
                ->where('material_id', $consumption->material_id)
                ->first();
            
            if ($lead) {
                // Lead charge per unit of material × consumption factor
                $materialLeadCharge = $lead->total_lead_charge * $consumption->consumption_factor;
                $totalLeadCharge += $materialLeadCharge;
            }
        }
        
        return $totalLeadCharge;
    }

    /**
     * Update or create a material lead and trigger rate recalculation
     * 
     * @param EstimationLead $lead
     * @param array $data
     * @return EstimationLead
     */
    public function updateMaterialLead(EstimationLead $lead, array $data): EstimationLead
    {
        $lead->update($data);
        $lead->calculateLeadCharge();
        $lead->save();
        
        // Trigger recalculation for all items in this estimation
        $this->updateEstimationRates($lead->estimation);
        
        return $lead;
    }

    /**
     * Get rate analysis breakdown for an item
     * Returns detailed component breakdown for reports
     * 
     * @param \App\Models\EstimationItem $item
     * @return array
     */
    public function getRateAnalysis($item): array
    {
        $rateModel = $item->getRateModel();
        $basicRate = $rateModel->rate ?? 0;
        
        $materialBreakdown = [];
        $totalLeadCharge = 0;
        
        // Get material-wise breakdown
        $consumptions = ItemMaterialConsumption::where('ssr_rate_id', $item->rate_id)
            ->with('material')
            ->get();
        
        foreach ($consumptions as $consumption) {
            $lead = EstimationLead::where('estimation_id', $item->estimation_id)
                ->where('material_id', $consumption->material_id)
                ->first();
            
            if ($lead) {
                $materialCharge = $lead->total_lead_charge * $consumption->consumption_factor;
                $totalLeadCharge += $materialCharge;
                
                $materialBreakdown[] = [
                    'material' => $consumption->material->name,
                    'consumption_factor' => $consumption->consumption_factor,
                    'lead_distance_km' => $lead->lead_distance_km,
                    'lead_rate_per_km' => $lead->lead_rate_per_km,
                    'total_lead_charge' => $lead->total_lead_charge,
                    'material_charge' => $materialCharge,
                ];
            }
        }
        
        $subtotal = $basicRate + $totalLeadCharge;
        $localTaxPercent = $item->estimation->contingency_percentage ?? 0;
        $localTax = $subtotal * ($localTaxPercent / 100);
        $finalRate = $subtotal + $localTax;
        
        return [
            'item_code' => $rateModel->item_code ?? 'N/A',
            'description' => $rateModel->description ?? 'N/A',
            'basic_rate' => $basicRate,
            'material_breakdown' => $materialBreakdown,
            'total_lead_charge' => $totalLeadCharge,
            'subtotal' => $subtotal,
            'local_tax_percent' => $localTaxPercent,
            'local_tax_amount' => $localTax,
            'final_rate' => round($finalRate, 2),
        ];
    }
}
