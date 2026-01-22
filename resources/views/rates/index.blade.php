@extends('layouts.app')

@section('title', 'Rates')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Rate Schedules</h1>
    <p class="text-gray-600">Browse DSR, SSR, and WRD rates</p>
</div>

<!-- Rate Type Tabs -->
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('rates.index', ['type' => 'dsr']) }}" 
               class="border-b-2 py-4 px-1 text-sm font-medium {{ request('type', 'dsr') == 'dsr' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                DSR Rates
            </a>
            <a href="{{ route('rates.index', ['type' => 'ssr']) }}" 
               class="border-b-2 py-4 px-1 text-sm font-medium {{ request('type') == 'ssr' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                SSR Rates
            </a>
            <a href="{{ route('rates.index', ['type' => 'wrd']) }}" 
               class="border-b-2 py-4 px-1 text-sm font-medium {{ request('type') == 'wrd' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                WRD Rates
            </a>
        </nav>
    </div>
</div>

<!-- Search -->
<div class="card mb-6">
    <form method="GET" action="{{ route('rates.index') }}" class="flex gap-4">
        <input type="hidden" name="type" value="{{ request('type', 'dsr') }}">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by item code or description..." class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('rates.index', ['type' => request('type', 'dsr')]) }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>
</div>

<!-- Rates Table -->
<div class="card">
    @if($rates->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Base Rate</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Labor Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rates as $rate)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $rate['item_code'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($rate['description'], 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $rate['unit'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">₹{{ number_format($rate['base_rate'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">₹{{ number_format($rate['labor_rate'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('rates.show', [$rate['id'], 'type' => request('type', 'dsr')]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $rates->appends(['type' => request('type', 'dsr'), 'search' => request('search')])->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No rates found.</p>
        </div>
    @endif
</div>
@endsection
