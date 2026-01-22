@extends('layouts.app')

@section('title', 'Manage Measurements')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Items & Measurements</h1>
            <p class="text-gray-600">
                <span class="font-medium">{{ $estimation['name'] }}</span>
                <span class="mx-2">•</span>
                Project: {{ $estimation['project']['name'] }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('estimations.leads', $estimation['id']) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Lead Settings
            </a>
            <a href="{{ route('estimations.edit', $estimation['id']) }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Estimation
            </a>
            <a href="{{ route('estimations.show', $estimation['id']) }}" class="btn btn-secondary">
                Back to View
            </a>
        </div>
    </div>

    {{-- Measurement Grid --}}
    <div class="card">
        @livewire('measurement-grid', ['estimationId' => $estimation['id']])
    </div>
</div>
@endsection
