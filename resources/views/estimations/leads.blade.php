@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <nav class="text-sm breadcrumbs mb-4">
            <ul class="flex items-center space-x-2 text-gray-600">
                <li><a href="{{ route('projects.index') }}" class="hover:text-blue-600">Projects</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                <li><a href="{{ route('estimations.show', $estimation['id']) }}" class="hover:text-blue-600">{{ $estimation['name'] }}</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                <li class="text-gray-900 font-medium">Lead Settings</li>
            </ul>
        </nav>

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Material Lead Settings</h1>
            <p class="text-gray-600 mt-2">Set quarry locations and lead distances for {{ $estimation['project']['name'] }}</p>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded shadow-sm">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="text-blue-900 font-semibold">How it Works</h3>
                <p class="text-blue-700 text-sm mt-1">Enter quarry location and distance for each material. Rates will automatically recalculate for all items using these materials.</p>
            </div>
        </div>
    </div>

    {{-- Lead Settings Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Material Transportation Settings
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                @foreach($leads as $index => $lead)
                    <div class="lead-row p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-all" 
                         data-material-id="{{ $lead['material_id'] }}"
                         data-lead-id="{{ $lead['id'] }}">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            {{-- Material Name --}}
                            <div class="col-span-12 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">Material</label>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-bold">
                                        {{ substr($lead['material_name'], 0, 1) }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $lead['material_name'] }}</div>
                                        <div class="text-xs text-gray-500">Unit: {{ $lead['material_unit'] }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Quarry Location --}}
                            <div class="col-span-12 md:col-span-3">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">Quarry Location</label>
                                <input 
                                    type="text" 
                                    class="quarry-location w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="Enter location..."
                                    value="{{ $lead['quarry_location'] }}"
                                >
                            </div>

                            {{-- Lead Distance --}}
                            <div class="col-span-6 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">Distance (km)</label>
                                <input 
                                    type="number" 
                                    class="lead-distance w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    value="{{ $lead['lead_distance_km'] }}"
                                >
                            </div>

                            {{-- Rate per km --}}
                            <div class="col-span-6 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">Rate/km (₹)</label>
                                <input 
                                    type="number" 
                                    class="lead-rate w-full px-3 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="0.00"
                                    step="0.01"
                                    min="0"
                                    value="{{ $lead['lead_rate_per_km'] }}"
                                >
                            </div>

                            {{-- Total Charge --}}
                            <div class="col-span-6 md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">Total Charge</label>
                                <div class="total-charge inline-flex items-center px-3 py-2 bg-green-50 border border-green-200 rounded-lg font-mono font-bold text-green-700 text-sm w-full justify-center">
                                    ₹{{ number_format($lead['total_lead_charge'], 2) }}
                                </div>
                            </div>

                            {{-- Save Button --}}
                            <div class="col-span-6 md:col-span-1">
                                <label class="block text-xs font-semibold text-transparent mb-1 uppercase">Action</label>
                                <button 
                                    class="save-lead w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all text-sm font-semibold shadow-sm hover:shadow-md"
                                    title="Save and recalculate"
                                >
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Status Message --}}
                        <div class="status-message mt-2 text-sm hidden"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Real-time Preview --}}
    <div class="mt-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 rounded-xl shadow-lg border border-blue-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Estimation Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600 mb-1">Items Count</div>
                <div id="items-count" class="text-2xl font-bold text-blue-600">-</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600 mb-1">Sub Total</div>
                <div id="sub-total" class="text-2xl font-bold text-green-600">₹ -</div>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                <div id="total-amount" class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">₹ -</div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex items-center justify-between">
        <a href="{{ route('estimations.manage', $estimation['id']) }}" class="btn btn-secondary">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Measurements
        </a>

        <a href="{{ route('estimations.show', $estimation['id']) }}" class="btn btn-primary">
            View Estimation
            <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const estimationId = {{ $estimation['id'] }};
    
    // Load initial totals
    loadTotals();
    
    // Save lead on button click
    document.querySelectorAll('.save-lead').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('.lead-row');
            await saveLead(row);
        });
    });
    
    // Also save on Enter key
    document.querySelectorAll('.lead-distance, .lead-rate, .quarry-location').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const row = this.closest('.lead-row');
                saveLead(row);
            }
        });
    });
    
    async function saveLead(row) {
        const materialId = row.dataset.materialId;
        const quarryLocation = row.querySelector('.quarry-location').value;
        const leadDistance = parseFloat(row.querySelector('.lead-distance').value) || 0;
        const leadRate = parseFloat(row.querySelector('.lead-rate').value) || 0;
        const statusMsg = row.querySelector('.status-message');
        const saveBtn = row.querySelector('.save-lead');
        
        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<svg class="w-4 h-4 mx-auto animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        try {
            const response = await fetch(`/estimations/${estimationId}/leads`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    material_id: materialId,
                    quarry_location: quarryLocation,
                    lead_distance_km: leadDistance,
                    lead_rate_per_km: leadRate,
                }),
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update total charge
                const totalCharge = leadDistance * leadRate;
                row.querySelector('.total-charge').textContent = `₹${totalCharge.toFixed(2)}`;
                
                // Show success message
                statusMsg.className = 'status-message mt-2 text-sm text-green-600 font-semibold';
                statusMsg.textContent = '✓ ' + data.message;
                statusMsg.classList.remove('hidden');
                
                // Reload totals
                await loadTotals();
                
                // Hide message after 3 seconds
                setTimeout(() => {
                    statusMsg.classList.add('hidden');
                }, 3000);
            }
        } catch (error) {
            statusMsg.className = 'status-message mt-2 text-sm text-red-600 font-semibold';
            statusMsg.textContent = '✗ Error: ' + error.message;
            statusMsg.classList.remove('hidden');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        }
    }
    
    async function loadTotals() {
        try {
            const response = await fetch(`/estimations/${estimationId}/leads/totals`);
            const data = await response.json();
            
            document.getElementById('items-count').textContent = data.items_count;
            document.getElementById('sub-total').textContent = `₹${parseFloat(data.sub_total || 0).toFixed(2)}`;
            document.getElementById('total-amount').textContent = `₹${parseFloat(data.total_amount || 0).toFixed(2)}`;
        } catch (error) {
            console.error('Failed to load totals:', error);
        }
    }
});
</script>
@endpush
@endsection
