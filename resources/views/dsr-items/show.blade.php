@extends('layouts.app')

@section('title', 'DSR Item Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('dsr-items.index') }}" class="text-indigo-600 hover:text-indigo-900 mb-2 inline-block">
        ← Back to DSR Items
    </a>
    <h1 class="text-3xl font-bold text-gray-900">{{ $item['item_code'] }}</h1>
</div>

<div class="card">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Item Details</h2>

    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <dt class="text-sm font-medium text-gray-500">Item Code</dt>
            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $item['item_code'] }}</dd>
        </div>

        @if(isset($item['spec_ref']) && $item['spec_ref'])
            <div>
                <dt class="text-sm font-medium text-gray-500">Specification Reference</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item['spec_ref'] }}</dd>
            </div>
        @endif

        <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Description</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $item['description'] }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-gray-500">Unit</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $item['unit'] }}</dd>
        </div>

        @if(isset($item['chapter_name']) && $item['chapter_name'])
            <div>
                <dt class="text-sm font-medium text-gray-500">Chapter</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item['chapter_name'] }}</dd>
            </div>
        @endif

        <div>
            <dt class="text-sm font-medium text-gray-500">Base Rate</dt>
            <dd class="mt-1 text-2xl font-bold text-green-600">₹{{ number_format($item['base_rate'] ?? 0, 2) }}</dd>
        </div>

        @if(isset($item['labor_rate']) && $item['labor_rate'])
            <div>
                <dt class="text-sm font-medium text-gray-500">Labor Rate</dt>
                <dd class="mt-1 text-2xl font-bold text-blue-600">₹{{ number_format($item['labor_rate'], 2) }}</dd>
            </div>
        @endif
    </dl>
</div>
@endsection
