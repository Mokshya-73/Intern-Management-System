@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4">
    <h2 class="text-2xl font-bold mb-6">📋 Your Complaints History</h2>

    @if($complaints->isEmpty())
        <p class="text-gray-600">You haven't submitted any complaints yet.</p>
    @else
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-4 py-2 border">Session</th>
                    <th class="px-4 py-2 border">Message</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $c)
                    <tr>
                        <td class="px-4 py-2 border">{{ $c->internSession->session->session_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $c->message }}</td>
                        <td class="px-4 py-2 border">
                            @if($c->status === 'resolved')
                                <span class="text-green-600 font-medium">Resolved</span>
                            @else
                                <span class="text-yellow-600 font-medium">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">{{ $c->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $complaints->links() }}</div>
    @endif
</div>
@endsection
