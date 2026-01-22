<?php

namespace App\Livewire;

use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\EstimationMeasurement;
use Livewire\Component;

class MeasurementGrid extends Component
{
    public $estimationId;
    public $estimation;
    public $items = [];
    public $expandedItemId = null;
    public $showRateModal = false;

    protected $listeners = ['itemsAdded' => 'handleItemsAdded'];

    public function mount($estimationId)
    {
        $this->estimationId = $estimationId;
        $this->loadEstimation();
    }

    public function handleItemsAdded($count)
    {
        // Reload the estimation data
        $this->loadEstimation();
        
        // Close the modal
        $this->showRateModal = false;
        
        // Optionally expand the last added item
        if (!empty($this->items)) {
            $this->expandedItemId = end($this->items)['id'];
        }
        
        // Show success message
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "{$count} item(s) added successfully!"
        ]);
    }

    public function loadEstimation()
    {
        $this->estimation = Estimation::with([
            'items.measurements' => function ($query) {
                $query->orderBy('row_number');
            }
        ])->findOrFail($this->estimationId);

        $this->items = $this->estimation->items->map(function ($item) {
            $rateModel = $item->getRateModel();
            return [
                'id' => $item->id,
                'rate_id' => $item->rate_id,
                'rate_type' => $item->rate_type,
                'item_code' => $rateModel->item_code ?? 'N/A',
                'description' => $rateModel->description ?? 'N/A',
                'unit' => $rateModel->unit ?? 'N/A',
                'quantity' => $item->quantity,
                'rate' => $item->rate,
                'amount' => $item->amount,
                'measurements' => $item->measurements->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'row_number' => $m->row_number,
                        'number' => $m->number,
                        'length' => $m->length,
                        'breadth' => $m->breadth,
                        'height' => $m->height,
                        'quantity' => $m->quantity,
                        'remarks' => $m->remarks,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }

    public function refreshItems()
    {
        $this->loadEstimation();
    }

    public function addRow($itemId)
    {
        $item = EstimationItem::findOrFail($itemId);
        
        $maxRowNumber = $item->measurements()->max('row_number') ?? 0;
        $maxSortOrder = $item->measurements()->max('sort_order') ?? 0;

        EstimationMeasurement::create([
            'estimation_item_id' => $itemId,
            'row_number' => $maxRowNumber + 1,
            'number' => 1,
            'length' => null,
            'breadth' => null,
            'height' => null,
            'quantity' => 0,
            'remarks' => null,
            'sort_order' => $maxSortOrder + 1,
        ]);

        $this->loadEstimation();
    }

    public function removeRow($measurementId)
    {
        $measurement = EstimationMeasurement::findOrFail($measurementId);
        $itemId = $measurement->estimation_item_id;
        
        $measurement->delete();

        // Recalculate item amount
        $item = EstimationItem::find($itemId);
        if ($item) {
            $item->calculateAmount();
        }

        $this->loadEstimation();
    }

    public function updateMeasurement($measurementId, $field, $value)
    {
        $measurement = EstimationMeasurement::findOrFail($measurementId);
        $measurement->$field = $value;

        // Calculate quantity: number × length × breadth × height
        $number = $measurement->number ?? 1;
        $length = $measurement->length ?? 1;
        $breadth = $measurement->breadth ?? 1;
        $height = $measurement->height ?? 1;

        $measurement->quantity = $number * $length * $breadth * $height;
        $measurement->save();

        // Recalculate parent item
        $measurement->estimationItem->calculateAmount();

        $this->loadEstimation();
    }

    public function toggleItem($itemId)
    {
        if ($this->expandedItemId === $itemId) {
            $this->expandedItemId = null;
        } else {
            $this->expandedItemId = $itemId;
        }
    }

    public function expandItem($itemId)
    {
        $this->expandedItemId = $itemId;
    }

    public function openRateModal()
    {
        $this->showRateModal = true;
    }

    public function closeRateModal()
    {
        $this->showRateModal = false;
    }

    public function render()
    {
        return view('livewire.measurement-grid');
    }
}
