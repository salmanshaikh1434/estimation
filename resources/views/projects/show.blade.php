@extends('layouts.app')

@section('title', $project['name'])

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $project['name'] }}</h1>
        <p class="text-gray-600">{{ $project['code'] }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('estimations.create', $project['id']) }}" class="btn btn-primary">
            Create Estimation
        </a>
        <a href="{{ route('projects.edit', $project['id']) }}" class="btn btn-secondary">
            Edit Project
        </a>
    </div>
</div>

<!-- Project Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2">
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Project Details</h2>
            
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project['client'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project['location'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $project['status'] == 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $project['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $project['status'] == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $project['status'] == 'on_hold' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $project['status'])) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">₹{{ number_format($project['total_amount'], 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project['start_date'] }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project['end_date'] }}</dd>
                </div>
                @if($project['description'])
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['description'] }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div>
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
            <dl class="space-y-3">
                @if($project['sanctioned_estimate_number'])
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Sanctioned Estimate No.</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['sanctioned_estimate_number'] }}</dd>
                    </div>
                @endif
                @if($project['financial_year'])
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Financial Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['financial_year'] }}</dd>
                    </div>
                @endif
                @if($project['prepared_by'])
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prepared By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['prepared_by'] }}</dd>
                    </div>
                @endif
                @if($project['checked_by'])
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Checked By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['checked_by'] }}</dd>
                    </div>
                @endif
                @if($project['approved_by'])
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $project['approved_by'] }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>

<!-- Estimations -->
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Estimations</h2>
        <a href="{{ route('estimations.create', $project['id']) }}" class="btn btn-primary">
            Add Estimation
        </a>
    </div>

    @if(count($project['estimations']) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project['estimations'] as $estimation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $estimation['name'] }}</div>
                                @if($estimation['description'])
                                    <div class="text-sm text-gray-500">{{ Str::limit($estimation['description'], 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $estimation['status'] == 'final' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($estimation['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $estimation['items_count'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($estimation['total_amount'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($estimation['created_at'])->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('estimations.show', $estimation['id']) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('estimations.edit', $estimation['id']) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No estimations created yet.</p>
            <a href="{{ route('estimations.create', $project['id']) }}" class="btn btn-primary">Create First Estimation</a>
        </div>
    @endif
</div>

<!-- Delete Project -->
<div class="mt-6">
    <form method="POST" action="{{ route('projects.destroy', $project['id']) }}" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Project</button>
    </form>
</div>
@endsection
