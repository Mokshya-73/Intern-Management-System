@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white border border-blue-800 rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4 text-center text-blue-900">Edit Profile</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('approver2.profile.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $approver2->name) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Designation</label>
            <input type="text" name="designation" value="{{ old('designation', $approver2->designation) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded">{{ old('description', $approver2->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">New Password (optional)</label>
            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded">
        </div>

        <div class="text-right">
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
