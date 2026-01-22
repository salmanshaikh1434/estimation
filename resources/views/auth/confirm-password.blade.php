@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Confirm Password</h2>
    <p class="text-sm text-gray-600 mb-6">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-input @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="btn btn-primary">
                Confirm
            </button>
        </div>
    </form>
</div>
@endsection
