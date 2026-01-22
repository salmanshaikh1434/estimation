<div class="p-6">
    {{-- Search and Filters --}}
    <div class="mb-6 space-y-4">
        {{-- Rate Type Selector --}}
        <div class="flex gap-2">
            <button 
                wire:click="$set('rateType', 'ssr')"
                class="px-4 py-2 rounded-lg font-semibold transition-all {{ $rateType === 'ssr' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                SSR Rates
            </button>
            <button 
                wire:click="$set('rateType', 'dsr')"
                class="px-4 py-2 rounded-lg font-semibold transition-all {{ $rateType === 'dsr' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                DSR Rates
            </button>
            <button 
                wire:click="$set('rateType', 'wrd')"
                class="px-4 py-2 rounded-lg font-semibold transition-all {{ $rateType === 'wrd' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
            >
                WRD Rates
            </button>
        </div>

        {{-- Search Box --}}
        <div class="relative">
            <input 
                type

="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search by item code or description..."
                class="w-full px-4 py-3 pl-12 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Category Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                <select 
                    wire:model.live="selectedCategory"
                    class="w-full px-4 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            @if($selectedCategory)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub-Category</label>
                    <select 
                        wire:model.live="selectedSubCategory"
                        class="w-full px-4 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Sub-Categories</option>
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory }}">{{ $subCategory }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    {{-- Selected Items Count --}}
    @if(count($selectedItems) > 0)
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-600 rounded">
            <div class="flex items-center justify-between">
                <span class="text-blue-900 font-semibold">
                    {{ count($selectedItems) }} item(s) selected
                </span>
                <button 
                    wire:click="addSelectedItems"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all shadow-md hover:shadow-lg"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Selected Items
                </button>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-600 rounded text-green-900">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-600 rounded text-red-900">
            {{ session('error') }}
        </div>
    @endif

    {{-- Rates Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto" style="max-height: 400px;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 sticky top-0">
                    <tr>
                        <th class="w-12 px-4 py-3"></th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Item Code</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Description</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-700">Unit</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700">Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($rates as $rate)
                        <tr class="hover:bg-blue-50 transition-colors cursor-pointer" wire:click="toggleItemSelection({{ $rate->id }})">
                            <td class="px-4 py-3 text-center">
                                <input 
                                    type="checkbox" 
                                    wire:model.live="selectedItems.{{ $rate->id }}"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                >
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $rate->item_code }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $rate->description }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $rate->unit }}</td>
                            <td class="px-4 py-3 text-sm text-right font-mono font-bold text-green-700">
                                ₹{{ number_format($rate->rate_non_scheduled ?? $rate->rate_scheduled ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-semibold">No rates found</p>
                                    <p class="text-sm">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($rates->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $rates->links() }}
            </div>
        @endif
    </div>

    {{-- Help Text --}}
    <div class="mt-4 text-sm text-gray-600 text-center">
        <svg class="w-5 h-5 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Click on a row or checkbox to select items. Multi-select is supported.
    </div>
</div>
