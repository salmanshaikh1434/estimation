@extends('layouts.guest')

@section('title', 'Two-Factor Challenge')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Two-Factor Authentication</h2>
    <p class="text-sm text-gray-600 mb-6">
        Please confirm access to your account by entering the authentication code provided by your authenticator application.
    </p>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <!-- Code -->
        <div class="mb-4">
            <label for="code" class="form-label">Code</label>
            <input id="code" type="text" name="code" autofocus autocomplete="one-time-code" class="form-input @error('code') border-red-500 @enderror">
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="btn btn-primary">
                Verify
            </button>
        </div>
    </form>
</div>
@endsection
