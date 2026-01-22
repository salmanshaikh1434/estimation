@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Project</h1>

    <div class="card">
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div class="md:col-span-2">
                    <label for="name" class="form-label">Project Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Code -->
                <div>
                    <label for="code" class="form-label">Project Code *</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required class="form-input @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">Status *</label>
                    <select id="status" name="status" required class="form-input @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Client -->
                <div>
                    <label for="client" class="form-label">Client *</label>
                    <input type="text" id="client" name="client" value="{{ old('client') }}" required class="form-input @error('client') border-red-500 @enderror">
                    @error('client')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="form-label">Location *</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required class="form-input @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="form-label">Start Date *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="form-input @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="form-label">End Date *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="form-input @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Face Sheet Template -->
                @if(isset($face_sheet_templates) && $face_sheet_templates->count() > 0)
                    <div class="md:col-span-2">
                        <label for="face_sheet_template_id" class="form-label">Face Sheet Template</label>
                        <select id="face_sheet_template_id" name="face_sheet_template_id" class="form-input">
                            <option value="">None</option>
                            @foreach($face_sheet_templates as $template)
                                <option value="{{ $template['id'] }}" {{ old('face_sheet_template_id') == $template['id'] ? 'selected' : '' }}>
                                    {{ $template['name'] }} - {{ $template['organization_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Additional Fields -->
                <div>
                    <label for="sanctioned_estimate_number" class="form-label">Sanctioned Estimate Number</label>
                    <input type="text" id="sanctioned_estimate_number" name="sanctioned_estimate_number" value="{{ old('sanctioned_estimate_number') }}" class="form-input">
                </div>

                <div>
                    <label for="financial_year" class="form-label">Financial Year</label>
                    <input type="text" id="financial_year" name="financial_year" value="{{ old('financial_year') }}" placeholder="e.g., 2023-24" class="form-input">
                </div>

                <div>
                    <label for="prepared_by" class="form-label">Prepared By</label>
                    <input type="text" id="prepared_by" name="prepared_by" value="{{ old('prepared_by') }}" class="form-input">
                </div>

                <div>
                    <label for="checked_by" class="form-label">Checked By</label>
                    <input type="text" id="checked_by" name="checked_by" value="{{ old('checked_by') }}" class="form-input">
                </div>

                <div class="md:col-span-2">
                    <label for="approved_by" class="form-label">Approved By</label>
                    <input type="text" id="approved_by" name="approved_by" value="{{ old('approved_by') }}" class="form-input">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Project</button>
            </div>
        </form>
    </div>
</div>
@endsection
