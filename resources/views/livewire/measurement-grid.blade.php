<div class="space-y-6">
    {{-- Action Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button wire:click="openRateModal" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item from Rates
                </button>
            </div>
            
            @if(count($items) > 0)
                <div class="text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                    <span class="font-semibold text-gray-900">{{ count($items) }}</span> item{{ count($items) > 1 ? 's' : '' }} added
                </div>
            @endif
        </div>
    </div>

    {{-- Items & Measurements Grid --}}
    @if(count($items) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                                    Description
                                </div>
                            </th>
                            <th class="w-20 px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-700">No.</th>
                            <th class="w-24 px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-700">L</th>
                            <th class="w-24 px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-700">B</th>
                            <th class="w-24 px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-700">D/H</th>
                            <th class="w-32 px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-700">Quantity</th>
                            <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Remarks</th>
                            <th class="w-20 px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($items as $index => $item)
                            {{-- Parent Item Row --}}
                            <tr class="group bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 cursor-pointer transition-all duration-200" wire:click="toggleItem({{ $item['id'] }})">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-200 {{ $expandedItemId === $item['id'] ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-600 text-white">{{ $index + 1 }}.0</span>
                                                <span class="text-sm font-semibold text-gray-900">{{ $item['item_code'] }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $item['description'] }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-600">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    Unit: <span class="font-medium text-gray-900">{{ $item['unit'] }}</span>
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Rate: <span class="font-medium text-gray-900">₹{{ number_format($item['rate'], 2) }}</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-right">
                                            <div class="text-xs text-gray-500 mb-1">Total Qty</div>
                                            <div class="text-2xl font-bold text-blue-700">{{ number_format($item['quantity'], 2) }}</div>
                                            <div class="text-xs text-gray-600 mt-1">₹{{ number_format($item['amount'], 2) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="7"></td>
                            </tr>

                            {{-- Measurement Rows (Expandable) --}}
                            @if($expandedItemId === $item['id'])
                                @if(count($item['measurements']) > 0)
                                    @foreach($item['measurements'] as $measurement)
                                        <tr class="hover:bg-blue-50/50 transition-colors duration-150" x-data="measurementRow({{ json_encode($measurement) }}, {{ $item['id'] }})">
                                            <td class="px-6 py-3 pl-16">
                                                <input 
                                                    type="text" 
                                                    x-model="remarks"
                                                    @blur="updateField('remarks', $event.target.value)"
                                                    class="w-full px-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent italic placeholder-gray-400 transition-all duration-200" 
                                                    placeholder="Enter sub-particular description..."
                                                >
                                            </td>
                                            <td class="px-2 py-3">
                                                <input 
                                                    type="number" 
                                                    x-model="number"
                                                    @input="calculateQuantity"
                                                    @blur="updateField('number', $event.target.value)"
                                                    class="w-full px-3 py-2 text-center border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium transition-all duration-200" 
                                                    min="1"
                                                    step="1"
                                                >
                                            </td>
                                            <td class="px-2 py-3">
                                                <input 
                                                    type="number" 
                                                    x-model="length"
                                                    @input="calculateQuantity"
                                                    @blur="updateField('length', $event.target.value)"
                                                    class="w-full px-3 py-2 text-center border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium transition-all duration-200" 
                                                    step="0.01"
                                                    placeholder="0.00"
                                                >
                                            </td>
                                            <td class="px-2 py-3">
                                                <input 
                                                    type="number" 
                                                    x-model="breadth"
                                                    @input="calculateQuantity"
                                                    @blur="updateField('breadth', $event.target.value)"
                                                    class="w-full px-3 py-2 text-center border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium transition-all duration-200" 
                                                    step="0.01"
                                                    placeholder="0.00"
                                                >
                                            </td>
                                            <td class="px-2 py-3">
                                                <input 
                                                    type="number" 
                                                    x-model="height"
                                                    @input="calculateQuantity"
                                                    @blur="updateField('height', $event.target.value)"
                                                    class="w-full px-3 py-2 text-center border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-medium transition-all duration-200" 
                                                    step="0.01"
                                                    placeholder="0.00"
                                                >
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="inline-flex items-center px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                                                    <span class="font-mono font-bold text-green-700" x-text="quantity.toFixed(2)"></span>
                                                </div>
                                            </td>
                                            <td class="px-2 py-3"></td>
                                            <td class="px-2 py-3 text-center">
                                                <button 
                                                    wire:click="removeRow({{ $measurement['id'] }})"
                                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200 hover:scale-110"
                                                    title="Delete measurement"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="bg-blue-50/30">
                                        <td colspan="8" class="px-6 py-8 text-center">
                                            <p class="text-sm text-gray-600 mb-4">No measurements added yet. Click the button below to add your first measurement.</p>
                                        </td>
                                    </tr>
                                @endif

                                {{-- Add Row Button --}}
                                <tr class="bg-gradient-to-r from-gray-50 to-blue-50">
                                    <td colspan="8" class="px-6 py-4 pl-16">
                                        <button 
                                            wire:click="addRow({{ $item['id'] }})"
                                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 hover:shadow-md"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Sub-Measurement
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Enhanced Empty State --}}
        <div class="bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-300 p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No Items Added Yet</h3>
                <p class="text-sm text-gray-600 mb-6">Start building your estimation by adding items from the rate schedule.</p>
                <button wire:click="openRateModal" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Your First Item
                </button>
            </div>
        </div>
    @endif

    {{-- Enhanced Totals Card --}}
    @if(count($items) > 0 && $estimation)
        <div class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 rounded-xl shadow-lg border border-blue-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Estimation Summary
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Sub Total</span>
                    <span class="text-lg font-semibold text-gray-900">₹ {{ number_format($estimation->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Royalty</span>
                    <span class="text-lg font-semibold text-gray-900">₹ {{ number_format($estimation->royalty_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Contingency <span class="text-xs text-gray-500">({{ $estimation->contingency_percentage }}%)</span></span>
                    <span class="text-lg font-semibold text-gray-900">₹ {{ number_format(($estimation->sub_total + $estimation->royalty_amount) * $estimation->contingency_percentage / 100, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">GST <span class="text-xs text-gray-500">({{ $estimation->gst_percentage }}%)</span></span>
                    <span class="text-lg font-semibold text-gray-900">₹ {{ number_format(($estimation->sub_total + $estimation->royalty_amount + (($estimation->sub_total + $estimation->royalty_amount) * $estimation->contingency_percentage / 100)) * $estimation->gst_percentage / 100, 2) }}</span>
                </div>
                <div class="mt-4 pt-4 border-t-2 border-blue-300">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-bold text-gray-900 uppercase tracking-wide">Total Amount</span>
                        <div class="text-right">
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                ₹ {{ number_format($estimation->total_amount, 2) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Including all taxes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Rate Selector Modal --}}
    @if($showRateModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-50">
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Select Item from Rate Schedule
                                </h3>
                                <button wire:click="closeRateModal" class="text-white/80 hover:text-white transition-colors">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="bg-white">
                            @livewire('rate-selector', ['estimationId' => $estimationId])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function measurementRow(measurement, itemId) {
    return {
        number: measurement.number || 1,
        length: measurement.length || null,
        breadth: measurement.breadth || null,
        height: measurement.height || null,
        quantity: measurement.quantity || 0,
        remarks: measurement.remarks || '',
        
        calculateQuantity() {
            const n = parseFloat(this.number) || 1;
            const l = parseFloat(this.length) || 1;
            const b = parseFloat(this.breadth) || 1;
            const h = parseFloat(this.height) || 1;
            this.quantity = n * l * b * h;
        },
        
        updateField(field, value) {
            @this.call('updateMeasurement', measurement.id, field, value);
        }
    }
}
</script>
