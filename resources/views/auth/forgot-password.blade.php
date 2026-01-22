@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Forgot your password?</h2>
    <p class="text-sm text-gray-600 mb-6">
        No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500 mr-4">
                Back to login
            </a>

            <button type="submit" class="btn btn-primary">
                Email Password Reset Link
            </button>
        </div>
    </form>
</div>
@endsection
