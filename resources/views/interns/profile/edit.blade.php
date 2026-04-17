@extends('layouts.app')

@section('content')
@include('layouts.headers.intern')
<div class="max-w-6xl mx-auto mt-10 shadow-md rounded bg-gray-100 p-6 sm:p-10 md:p-20 border-2 border-blue-800">

    <h2 class="text-xl font-bold mb-6">Edit Profile</h2>

    <form action="{{ route('intern.profile.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $intern->name) }}" class="w-full border p-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Certificate Name</label>
                    <input type="text" name="certificate_name" value="{{ old('certificate_name', $intern->certificate_name) }}" class="w-full border p-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Mobile</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $intern->mobile) }}" class="w-full border p-2 rounded">
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label class="block font-medium mb-1">New Password</label>
                    <input type="password" name="password" class="w-full border p-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Description</label>
                    <textarea name="description" class="w-full border p-2 rounded" rows="3">{{ old('description', $intern->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mt-8 gap-4">
            <a href="{{ route('intern.profile.show') }}" class="bg-red-900 text-white px-4 py-2 rounded text-center hover:bg-red-800">
                Cancel
            </a>
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">
                Update
            </button>
        </div>
    </form>
</div>
@endsection