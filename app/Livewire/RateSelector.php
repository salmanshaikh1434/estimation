<?php

namespace App\Livewire;

use App\Models\SsrRate;
use App\Models\DsrRate;
use App\Models\WrdRate;
use App\Models\Estimation;
use App\Models\EstimationItem;
use Livewire\Component;
use Livewire\WithPagination;

class RateSelector extends Component
{
    use WithPagination;

    public $estimationId;
    public $rateType = 'ssr'; // Default to SSR
    public $search = '';
    public $selectedCategory = '';
    public $selectedSubCategory = '';
    public $selectedItems = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'rateType' => ['except' => 'ssr'],
    ];

    public function mount($estimationId)
    {
        $this->estimationId = $estimationId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRateType()
    {
        $this->resetPage();
        $this->selectedCategory = '';
        $this->selectedSubCategory = '';
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
        $this->selectedSubCategory = '';
    }

    public function toggleItemSelection($rateId)
    {
        if (isset($this->selectedItems[$rateId])) {
            unset($this->selectedItems[$rateId]);
        } else {
            $this->selectedItems[$rateId] = true;
        }
    }

    public function addSelectedItems()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Please select at least one item.');
            return;
        }

        $estimation = Estimation::findOrFail($this->estimationId);
        $addedCount = 0;

        foreach (array_keys($this->selectedItems) as $rateId) {
            // Check if item already exists
            $exists = EstimationItem::where('estimation_id', $this->estimationId)
                ->where('rate_id', $rateId)
                ->where('rate_type', $this->rateType)
                ->exists();

            if (!$exists) {
                // Get the rate model
                $rateModel = $this->getRateModel($rateId);
                
                if ($rateModel) {
                    EstimationItem::create([
                        'estimation_id' => $this->estimationId,
                        'rate_id' => $rateId,
                        'rate_type' => $this->rateType,
                        'quantity' => 0,
                        'rate' => $this->getBasicRate($rateModel),
                        'amount' => 0,
                        'sort_order' => EstimationItem::where('estimation_id', $this->estimationId)->max('sort_order') + 1,
                    ]);
                    $addedCount++;
                }
            }
        }

        // Clear selections
        $this->selectedItems = [];

        // Emit event to refresh measurement grid
        $this->dispatch('itemsAdded', count: $addedCount);

        session()->flash('success', "{$addedCount} item(s) added successfully!");
    }

    protected function getRateModel($rateId)
    {
        return match($this->rateType) {
            'ssr' => SsrRate::find($rateId),
            'dsr' => DsrRate::find($rateId),
            'wrd' => WrdRate::find($rateId),
            default => null,
        };
    }

    protected function getBasicRate($rateModel)
    {
        // Use non-scheduled rate by default, fallback to scheduled
        return $rateModel->rate_non_scheduled ?? $rateModel->rate_scheduled ?? 0;
    }

    public function getRates()
    {
        $query = match($this->rateType) {
            'ssr' => SsrRate::query(),
            'dsr' => DsrRate::query(),
            'wrd' => WrdRate::query(),
            default => SsrRate::query(),
        };

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('item_code', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        // SubCategory filter
        if ($this->selectedSubCategory) {
            $query->where('sub_category', $this->selectedSubCategory);
        }

        return $query->orderBy('item_code')->paginate(15);
    }

    public function getCategories()
    {
        $model = match($this->rateType) {
            'ssr' => SsrRate::class,
            'dsr' => DsrRate::class,
            'wrd' => WrdRate::class,
            default => SsrRate::class,
        };

        return $model::distinct()->pluck('category')->filter()->sort()->values();
    }

    public function getSubCategories()
    {
        if (!$this->selectedCategory) {
            return collect();
        }

        $model = match($this->rateType) {
            'ssr' => SsrRate::class,
            'dsr' => DsrRate::class,
            'wrd' => WrdRate::class,
            default => SsrRate::class,
        };

        return $model::where('category', $this->selectedCategory)
            ->distinct()
            ->pluck('sub_category')
            ->filter()
            ->sort()
            ->values();
    }

    public function render()
    {
        return view('livewire.rate-selector', [
            'rates' => $this->getRates(),
            'categories' => $this->getCategories(),
            'subCategories' => $this->getSubCategories(),
        ]);
    }
}
