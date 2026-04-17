@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-center mb-6">Remove {{ ucfirst($type) }} Assignment</h2>

    <form method="POST" action="{{ route('admin.structure.remove.store') }}">
        @csrf

        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $id }}">

        <div class="mb-4">
            <label class="block mb-2 font-medium">Reason for Removal</label>
            <select name="reason" class="w-full border rounded p-2" required>
                <option value="">-- Select Reason --</option>
                @foreach($removalReasons as $reason)
                    <option value="{{ $reason }}">{{ $reason }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Remove Assignment</button>
        <a href="{{ route('admin.structure.assign') }}" class="ml-4 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
