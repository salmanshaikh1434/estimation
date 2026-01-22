@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        Create New Project
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" action="{{ route('projects.index') }}" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="form-input">
        </div>
        <div class="w-48">
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request('search') || request('status'))
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Clear</a>
        @endif
    </form>
</div>

<!-- Projects List -->
<div class="card">
    @if($projects->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $project['code'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project['client'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project['location'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $project['status'] == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $project['status'] == 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $project['status'] == 'on_hold' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project['status'])) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $project['estimations_count'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">₹{{ number_format($project['total_amount'], 2) }}</td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('projects.show', $project['id']) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                <a href="{{ route('projects.edit', $project['id']) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No projects found.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Your First Project</a>
        </div>
    @endif
</div>
@endsection
