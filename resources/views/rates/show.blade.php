@extends('layouts.app')

@section('title', 'Rate Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('rates.index', ['type' => request('type', 'dsr')]) }}" class="text-indigo-600 hover:text-indigo-900 mb-2 inline-block">
        ← Back to Rates
    </a>
    <h1 class="text-3xl font-bold text-gray-900">{{ $rate['item_code'] }}</h1>
    <p class="text-gray-600">{{ strtoupper(request('type', 'dsr')) }} Rate</p>
</div>

<div class="card">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Rate Details</h2>

    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <dt class="text-sm font-medium text-gray-500">Item Code</dt>
            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $rate['item_code'] }}</dd>
        </div>

        @if(isset($rate['spec_ref']) && $rate['spec_ref'])
            <div>
                <dt class="text-sm font-medium text-gray-500">Specification Reference</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $rate['spec_ref'] }}</dd>
            </div>
        @endif

        <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Description</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $rate['description'] }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500">Unit</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $rate['unit'] }}</dd>
        </div>

        @if(isset($rate['chapter_name']) && $rate['chapter_name'])
            <div>
                <dt class="text-sm font-medium text-gray-500">Chapter</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $rate['chapter_name'] }}</dd>
            </div>
        @endif

        <div>
            <dt class="text-sm font-medium text-gray-500">Base Rate</dt>
            <dd class="mt-1 text-2xl font-bold text-green-600">₹{{ number_format($rate['base_rate'] ?? 0, 2) }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500">Labor Rate</dt>
            <dd class="mt-1 text-2xl font-bold text-blue-600">₹{{ number_format($rate['labor_rate'] ?? 0, 2) }}</dd>
        </div>

        @if(isset($rate['category']) && $rate['category'])
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Category</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $rate['category'] }}</dd>
            </div>
        @endif

        @if(isset($rate['remarks']) && $rate['remarks'])
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Remarks</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $rate['remarks'] }}</dd>
            </div>
        @endif
    </dl>
</div>
@endsection
