@extends('layouts.app')

@section('title', 'DSR Items')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">DSR Items</h1>
    <p class="text-gray-600">Browse District Schedule of Rates items</p>
</div>

<!-- Search -->
<div class="card mb-6">
    <form method="GET" action="{{ route('dsr-items.index') }}" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by item code or description..." class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('dsr-items.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>
</div>

<!-- DSR Items Table -->
<div class="card">
    @if($items->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Base Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['item_code'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($item['description'], 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item['unit'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">₹{{ number_format($item['base_rate'] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('dsr-items.show', $item['id']) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $items->appends(['search' => request('search')])->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No DSR items found.</p>
        </div>
    @endif
</div>
@endsection
