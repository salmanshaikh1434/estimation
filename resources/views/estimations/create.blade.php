@extends('layouts.app')

@section('title', 'Create Estimation')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Estimation</h1>
    <p class="text-gray-600 mb-6">Project: {{ $project['name'] }} ({{ $project['code'] }})</p>

    <div class="card">
        <form method="POST" action="{{ route('estimations.store', $project['id']) }}">
            @csrf

            <div class="space-y-6">
                <!-- Estimation Name -->
                <div>
                    <label for="name" class="form-label">Estimation Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="3" class="form-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rate Type -->
                <div>
                    <label for="rate_type" class="form-label">Rate Type *</label>
                    <select id="rate_type" name="rate_type" required class="form-input @error('rate_type') border-red-500 @enderror">
                        <option value="dsr" {{ old('rate_type') == 'dsr' ? 'selected' : '' }}>DSR</option>
                        <option value="ssr" {{ old('rate_type') == 'ssr' ? 'selected' : '' }}>SSR</option>
                        <option value="wrd" {{ old('rate_type') == 'wrd' ? 'selected' : '' }}>WRD</option>
                        <option value="mixed" {{ old('rate_type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                    @error('rate_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Royalty Amount -->
                    <div>
                        <label for="royalty_amount" class="form-label">Royalty Amount</label>
                        <input type="number" id="royalty_amount" name="royalty_amount" value="{{ old('royalty_amount', 0) }}" step="0.01" min="0" class="form-input">
                    </div>

                    <!-- Contingency % -->
                    <div>
                        <label for="contingency_percentage" class="form-label">Contingency %</label>
                        <input type="number" id="contingency_percentage" name="contingency_percentage" value="{{ old('contingency_percentage', 0) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>

                    <!-- GST % -->
                    <div>
                        <label for="gst_percentage" class="form-label">GST %</label>
                        <input type="number" id="gst_percentage" name="gst_percentage" value="{{ old('gst_percentage', 18) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">Status *</label>
                    <select id="status" name="status" required class="form-input">
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="final" {{ old('status') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('projects.show', $project['id']) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create & Add Items</button>
            </div>
        </form>
    </div>
</div>
@endsection
