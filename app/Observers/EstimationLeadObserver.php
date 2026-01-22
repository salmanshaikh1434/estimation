<?php

namespace App\Observers;

use App\Models\EstimationLead;
use App\Services\LeadService;

class EstimationLeadObserver
{
    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Handle the EstimationLead "created" event.
     * When a new lead is added, recalculate all item rates
     */
    public function created(EstimationLead $estimationLead): void
    {
        $this->leadService->updateEstimationRates($estimationLead->estimation);
    }

    /**
     * Handle the EstimationLead "updated" event.
     * When lead distance or rate changes, recalculate all item rates
     */
    public function updated(EstimationLead $estimationLead): void
    {
        // Only trigger if distance or rate changed
        if ($estimationLead->wasChanged(['lead_distance_km', 'lead_rate_per_km'])) {
            $this->leadService->updateEstimationRates($estimationLead->estimation);
        }
    }

    /**
     * Handle the EstimationLead "deleted" event.
     * When a lead is removed, recalculate all item rates
     */
    public function deleted(EstimationLead $estimationLead): void
    {
        $this->leadService->updateEstimationRates($estimationLead->estimation);
    }
}
