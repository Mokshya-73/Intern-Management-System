@extends('layouts.app')

@section('content')

@php
    // Ensure $iSessions is available for intern_nav
    $iSessions = \App\Models\ISession::all();
@endphp

<!-- Include the intern_nav.blade.php file and pass the intern data -->
@include('layouts.headers.intern', ['intern' => $intern])

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Your Complaints</h2>

    <!-- Displaying success or error message -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table for displaying complaints -->
    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Complaint ID</th>
                    <th class="px-4 py-2 text-left">Complaint</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Resolved By</th>
                    <th class="px-4 py-2 text-left">Resolution</th>
                    <th class="px-4 py-2 text-left">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $complaint->id }}</td>
                        <td class="px-4 py-2">{{ $complaint->complaint }}</td>
                        <td class="px-4 py-2">
                            <span class="text-sm {{ $complaint->status == 'resolved' ? 'text-green-500' : 'text-red-500' }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($complaint->status == 'resolved')
                                {{ $complaint->resolved_by }}
                            @else
                                Not resolved yet
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($complaint->status == 'resolved')
                                {{ $complaint->reason_for_removal }}
                            @else
                                No resolution yet
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $complaint->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $complaints->links() }}
</div>

@endsection
