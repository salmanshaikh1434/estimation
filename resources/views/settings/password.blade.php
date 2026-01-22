@extends('layouts.app')

@section('title', 'Settings - Password')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Change Password</h1>

    <div class="card">
        <form method="POST" action="{{ route('settings.password.update') }}">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="form-label">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password" class="form-input @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="form-label">New Password *</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" class="form-input @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="form-input">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
