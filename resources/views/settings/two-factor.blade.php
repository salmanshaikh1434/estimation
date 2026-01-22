@extends('layouts.app')

@section('title', 'Settings - Two-Factor Authentication')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Two-Factor Authentication</h1>

    <div class="card">
        @if($twoFactorEnabled ?? false)
            <div class="mb-6">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-green-800">Two-factor authentication is enabled</span>
                    </div>
                    <form method="POST" action="{{ route('two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Disable</button>
                    </form>
                </div>
            </div>

            @if(isset($recoveryCodes) && count($recoveryCodes) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Recovery Codes</h3>
                    <p class="text-sm text-gray-600 mb-3">Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor authentication device is lost.</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                            @foreach($recoveryCodes as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Regenerate Recovery Codes</button>
                    </form>
                </div>
            @endif
        @else
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Enable Two-Factor Authentication</h3>
                <p class="text-sm text-gray-600 mb-4">Add additional security to your account using two-factor authentication.</p>
                
                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Enable Two-Factor Authentication</button>
                </form>
            </div>
        @endif

        @if(isset($qrCode))
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Scan QR Code</h3>
                <p class="text-sm text-gray-600 mb-4">Scan this QR code with your authenticator app.</p>
                <div class="bg-white p-4 inline-block rounded-lg border">
                    {!! $qrCode !!}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
