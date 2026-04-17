@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow border-2 border-blue-800">

    <h2 class="text-xl font-bold text-center text-gray-800 mb-6">Update Credentials for Reg No: {{ $user->reg_no }}</h2>

    {{-- Email Section --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Update Email</h3>

        @if(session('email_success'))
            <div class="mb-3 p-3 bg-green-100 text-green-700 rounded">{{ session('email_success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.users.update.email', $user->reg_no) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">Update Email</button>
        </form>
    </div>

    <hr class="my-6">

    {{-- Password Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-1">Update Password</h3>

        @if(session('password_success'))
            <div class="mb-3 p-3 bg-green-100 text-green-700 rounded">{{ session('password_success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.users.update.password', $user->reg_no) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">Update Password</button>
        </form>
    </div>
</div>
@endsection
