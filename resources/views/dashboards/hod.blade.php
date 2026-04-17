@extends('layouts.app')

@section('content')
@php
    use App\Models\Complaint;
@endphp

<div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">HOD Dashboard</h2>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 border border-green-400 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Supervisor Approved Sessions Awaiting HOD Approval --}}
    <div class="mt-10 p-6 bg-white border-2 border-blue-300 rounded shadow">
        <h2 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center md-10"
>Supervisor Approved Sessions Awaiting HOD Approval</h2>

        @if($approvedSessions->count())
            <div class="overflow-x-auto mb-6">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-100 text-left text-sm">
                            <th class="px-4 py-2">Intern</th>
                            <th class="px-4 py-2">Reg No</th>
                            <th class="px-4 py-2">Project</th>
                            <th class="px-4 py-2">Session</th>
                            <th class="px-4 py-2">Supervisor</th>
                            <th class="px-4 py-2">HOD Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedSessions as $session)
                            <tr class="bg-white border-b text-sm">
                                <td class="px-4 py-2">{{ $session->intern->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $session->intern->reg_no ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $session->project_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $session->session_id ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $session->supervisor->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($session->hod_approved)
                                        <span class="text-green-600 font-semibold">Approved</span>
                                    @else
                                        <form method="POST" action="{{ route('hod.intern-sessions.approve', $session->id) }}">
                                            @csrf
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-red-500 italic">No approved sessions found for HOD approval.</p>
        @endif

        {{-- Complaints Tabs Inside Card --}}
        <div x-data="{ tab: 'supervisor' }">
            <div class="flex space-x-4 mb-4 mt-20">
                <button 
                    @click="tab = 'supervisor'" 
                    :class="tab === 'supervisor' 
                        ? 'bg-blue-800 text-white' 
                        : 'bg-blue-800 text-white hover:bg-blue-600'" 
                    class="px-4 py-2 rounded-lg font-semibold shadow">
                    Supervisor Complaints
                </button>
                <button 
                    @click="tab = 'intern'" 
                    :class="tab === 'intern' 
                        ? 'bg-green-600 text-white' 
                        : 'bg-green-700 text-white hover:bg-green-800'" 
                    class="px-4 py-2 rounded-lg font-semibold shadow">
                    Intern Session Complaints
                </button>
            </div>

            {{-- Supervisor Complaints --}}
            <div x-show="tab === 'supervisor'" class="transition-all duration-300 mt-10">
                <div>
                    <h3 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Supervisor Complaints</h3>

                    @if($complaints->count())
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse text-sm">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-4 py-2">ID</th>
                                        <th class="px-4 py-2">Intern</th>
                                        <th class="px-4 py-2">Complaint</th>
                                        <th class="px-4 py-2">Supervisor</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $complaint->id }}</td>
                                            <td class="px-4 py-2">{{ $complaint->intern->name ?? 'No Name' }}</td>
                                            <td class="px-4 py-2">{{ $complaint->complaint }}</td>
                                            <td class="px-4 py-2">{{ $complaint->supervisor->name ?? 'No Name' }}</td>
                                            <td class="px-4 py-2">{{ $complaint->status }}</td>
                                            <td class="px-4 py-2">
                                                @if($complaint->status === 'pending')
                                                    <form action="{{ route('complaints.resolve', $complaint->id) }}" method="POST">
                                                        @csrf
                                                        <textarea name="resolution" required class="w-full border p-2 rounded mb-2 resize-none" rows="2"></textarea>
                                                        <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                                            Resolve
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-green-700 font-semibold">Resolved</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-red-600">No complaints available.</p>
                    @endif
                </div>
            </div>

            {{-- Intern Session Complaints --}}
            <div x-show="tab === 'intern'" class="transition-all duration-300 mt-8">
                <div >
                    <h3 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Intern Session Complaints</h3>

                    @if($internComplaints->count())
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse text-sm">
                                <thead class="bg-red-100">
                                    <tr>
                                        <th class="px-4 py-2">ID</th>
                                        <th class="px-4 py-2">Intern</th>
                                        <th class="px-4 py-2">Session</th>
                                        <th class="px-4 py-2">Message</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($internComplaints as $c)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $c->id }}</td>
                                            <td class="px-4 py-2">{{ $c->intern->name ?? $c->intern_reg_no }}</td>
                                            <td class="px-4 py-2">{{ $c->internSession->session->session_name ?? 'N/A' }}</td>
                                            <td class="px-4 py-2">{{ $c->message }}</td>
                                            <td class="px-4 py-2">
                                                @if($c->status === 'resolved')
                                                    <span class="text-green-600 font-semibold">Resolved</span>
                                                @else
                                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($c->status === 'pending')
                                                    <form action="{{ route('hod.intern_complaints.resolve', $c->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                            Mark as Resolved
                                                        </button>
                                                    </form>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-red-600">No intern session complaints.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
