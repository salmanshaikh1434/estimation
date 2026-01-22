@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Projects Card -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Projects</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $projectsCount ?? 0 }}</p>
            <a href="{{ route('projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                View all projects →
            </a>
        </div>

        <!-- Estimations Card -->
        <div class="bg-green-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-900 mb-2">Estimations</h3>
            <p class="text-3xl font-bold text-green-600">{{ $estimationsCount ?? 0 }}</p>
            <a href="{{ route('projects.index') }}" class="text-sm text-green-600 hover:text-green-700 mt-2 inline-block">
                View projects to estimate →
            </a>
        </div>

        <!-- Rates Card -->
        <div class="bg-purple-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-900 mb-2">Rates</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $ratesCount ?? 0 }}</p>
            <a href="{{ route('rates.index') }}" class="text-sm text-purple-600 hover:text-purple-700 mt-2 inline-block">
                Browse rates →
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-600">Welcome to your dashboard!</p>
        </div>
    </div>
</div>
@endsection
