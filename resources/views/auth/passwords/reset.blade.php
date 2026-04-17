@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">
    <h2 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Reset Your Password</h2>

    {{-- ✅ Success Message --}}
    @if(session('reset_success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
            ✅ Your password has been reset successfully!
        </div>
    @endif

    {{-- ✅ Password Reset Form --}}
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-4">
            <label class="block">New Password</label>
            <input type="password" name="password" class="w-full border px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border px-3 py-2" required>
        </div>

        <button type="submit" class="bg-blue-900 hover:bg-blue-600  text-white px-4 py-2 rounded w-full">Reset Password</button>
    </form>

    {{-- ✅ NIC-based Password Reveal Card --}}
    @if(session('reset_success'))
        <div class="mt-6 bg-gray-100 p-4 rounded shadow text-center">
            <h3 class="font-semibold mb-2">🔒 Enter NIC to view your password</h3>

            <form method="POST" action="{{ route('password.reveal') }}">
                @csrf
                <input type="hidden" name="actual_password" value="{{ session('password_plain') }}">
                <input type="text" name="nic" placeholder="Enter your NIC" required class="w-full border rounded px-3 py-2 mb-3">
                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded">Unlock</button>
            </form>

            @if(session('revealed_password'))
                <div class="mt-3 bg-white p-3 border rounded shadow font-mono">
                    🔑 <strong>Password:</strong> {{ session('revealed_password') }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
