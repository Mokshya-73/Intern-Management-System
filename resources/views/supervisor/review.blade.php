@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Review & Approve Session</h2>

    <p class="mb-2"><strong>Intern:</strong> {{ $session->intern->name }} ({{ $session->intern->reg_no }})</p>
    <p class="mb-2"><strong>Project Name:</strong> {{ $session->project_name }}</p>
    @if($session->project_path)
        <p class="mb-4"><strong>File:</strong>
            <a href="{{ asset('storage/' . $session->project_path) }}" class="text-blue-600 underline" target="_blank">Download</a>
        </p>
    @endif

    <form action="{{ route('supervisor.review.update', $session->id) }}" method="POST">
        @csrf

        @foreach ($session->tasks as $index => $task)
            <div class="mb-6 border-t pt-4">
                @if($task->id)
                    <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task->id }}">
                @endif

                <label class="block font-medium">Task Name</label>
                <input type="text" name="tasks[{{ $index }}][task_name]" value="{{ $task->task_name }}" class="w-full border rounded px-3 py-2">

                <label class="block font-medium mt-2">Rating (1-5)</label>
                <input type="number" name="tasks[{{ $index }}][rating]" value="{{ $task->rating }}" min="1" max="5" class="w-full border rounded px-3 py-2">

                <label class="block font-medium mt-2">Description</label>
                <textarea name="tasks[{{ $index }}][description]" class="w-full border rounded px-3 py-2">{{ $task->description }}</textarea>
            </div>
        @endforeach

        <div class="mb-4">
            <label class="block font-medium">Supervisor Feedback</label>
            <textarea name="supervisor_feedback" class="w-full border rounded px-3 py-2">{{ $session->supervisor_feedback }}</textarea>
        </div>

        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Approve Session</button>
    </form>
</div>
@endsection
