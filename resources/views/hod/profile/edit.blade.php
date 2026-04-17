@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-bold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-10">
        Edit Profile
    </h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('hod.profile.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left column -->
            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $hod->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">New Password (optional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded">
                </div>

                <div>
                    <label class="block text-sm font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded">
                </div>
            </div>

            <!-- Right column -->
            <div class="flex flex-col">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded resize-none">{{ old('description', $hod->description) }}</textarea>
            </div>
        </div>

        <div class="text-right mt-6">
            <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
