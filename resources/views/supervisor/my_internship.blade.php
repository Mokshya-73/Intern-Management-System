@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 mt-10 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">🎓 My Internship History</h2>

    @if($intern)
        <div class="mb-4">
            <p><strong>Name:</strong> {{ $intern->name }}</p>
            <p><strong>Reg No:</strong> {{ $intern->reg_no }}</p>
            <p><strong>Status:</strong> {{ $intern->status }}</p>
        </div>

        @if($internSessions->count() > 0)
            <table class="table-auto w-full border text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Session</th>
                        <th class="px-4 py-2">Tasks</th>
                        <th class="px-4 py-2">Supervisor</th>
                        <th class="px-4 py-2">Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($internSessions as $session)
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $session->session->session_name ?? 'N/A' }}<br>
                                <small>{{ $session->session->session_time_period ?? '' }}</small>
                            </td>
                            <td class="border px-4 py-2">
                                <ul class="list-disc pl-5">
                                    @forelse($session->tasks as $task)
                                        <li>{{ $task->task_name }} (Rating: {{ $task->rating ?? 'N/A' }})</li>
                                    @empty
                                        <li>No tasks assigned</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="border px-4 py-2">{{ $session->supervisor->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $session->supervisor_feedback ?? 'No feedback' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-600 mt-4">No internship sessions found.</p>
        @endif
    @else
        <p class="text-gray-600">No previous internship record found for this supervisor.</p>
    @endif
</div>
@endsection
