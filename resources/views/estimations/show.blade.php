@extends('layouts.app')

@section('title', $estimation['name'])

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-start mb-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $estimation['name'] }}</h1>
            <p class="text-gray-600">Project: {{ $estimation['project']['name'] }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('estimations.edit', $estimation['id']) }}" class="btn btn-secondary">Edit</a>
            <button onclick="window.print()" class="btn btn-primary">Print</button>
        </div>
    </div>
</div>

<!-- Estimation Summary -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
    <div class="card">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
            {{ $estimation['status'] == 'final' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            {{ ucfirst($estimation['status']) }}
        </span>
    </div>
    <div class="card">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Rate Type</h3>
        <p class="text-lg font-semibold text-gray-900">{{ strtoupper($estimation['rate_type']) }}</p>
    </div>
    <div class="card">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Sub Total</h3>
        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($estimation['sub_total'] ?? 0, 2) }}</p>
    </div>
    <div class="card">
        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Amount</h3>
        <p class="text-lg font-semibold text-green-600">₹{{ number_format($estimation['total_amount'] ?? 0, 2) }}</p>
    </div>
</div>

<!-- Items List -->
<div class="card">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Estimation Items</h2>

    @if(count($estimation['items']) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Rate</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($estimation['items'] as $item)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['item_code'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['description'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['unit'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ number_format($item['quantity'], 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">₹{{ number_format($item['rate'], 2) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">₹{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Sub Total:</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">₹{{ number_format($estimation['sub_total'] ?? 0, 2) }}</td>
                    </tr>
                    @if($estimation['royalty_amount'] > 0)
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-sm text-gray-700 text-right">Royalty:</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₹{{ number_format($estimation['royalty_amount'], 2) }}</td>
                        </tr>
                    @endif
                    @if($estimation['contingency_percentage'] > 0)
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-sm text-gray-700 text-right">Contingency ({{ $estimation['contingency_percentage'] }}%):</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₹{{ number_format(($estimation['sub_total'] ?? 0) * $estimation['contingency_percentage'] / 100, 2) }}</td>
                        </tr>
                    @endif
                    @if($estimation['gst_percentage'] > 0)
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-sm text-gray-700 text-right">GST ({{ $estimation['gst_percentage'] }}%):</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">₹{{ number_format(($estimation['sub_total'] ?? 0) * $estimation['gst_percentage'] / 100, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-base font-bold text-gray-900 text-right">Total Amount:</td>
                        <td class="px-4 py-3 text-base font-bold text-green-600 text-right">₹{{ number_format($estimation['total_amount'] ?? 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No items added yet.</p>
            <a href="{{ route('estimations.edit', $estimation['id']) }}" class="btn btn-primary">Add Items</a>
        </div>
    @endif
</div>
@endsection
