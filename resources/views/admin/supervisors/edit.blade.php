@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 shadow-md rounded-md mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Supervisor</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.supervisors.update', $supervisor->reg_no) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf

        <div>
            <label class="block font-medium">Full Name</label>
            <input type="text" name="name" value="{{ $supervisor->name }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" value="{{ $core->email }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Password <span class="text-sm text-gray-500">(Leave blank to keep current)</span></label>
            <input type="password" name="password" class="w-full border rounded px-4 py-2">
        </div>

        <div>
            <label class="block font-medium">University</label>
            <input type="text" name="university" value="{{ $supervisor->university }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Location</label>
            <input type="text" name="location" value="{{ $supervisor->location }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="md:col-span-2">
            <label class="block font-medium">Designation</label>
            <input type="text" name="designation" value="{{ $supervisor->designation }}" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="md:col-span-2 text-right">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Supervisor</button>
        </div>
    </form>
</div>
@endsection
