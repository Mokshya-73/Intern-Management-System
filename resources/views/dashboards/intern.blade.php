@extends('layouts.app')

@section('content')
@php
    use App\Models\ISession;
    use App\Models\InternSession;
    use App\Models\Complaint;

    $iSessions = ISession::all();
    $internSessions = InternSession::with(['supervisor', 'tasks'])
        ->where('reg_no', $intern->reg_no)
        ->get();

    $allApproved2 = $internSessions->count() > 0 && $internSessions->every(fn($s) => $s->approver2_approved);
    $firstSessionId = $iSessions->first()->id ?? null;
    $firstSessionName = $firstSessionId ? $iSessions->firstWhere('id', $firstSessionId)->session_name . ' (' . $iSessions->firstWhere('id', $firstSessionId)->session_time_period . ')' : 'Select Session';

    $complaints = Complaint::where('intern_reg_no', $intern->reg_no)
                           ->where('status', 'pending')
                           ->get();
@endphp

<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800" x-data="{
    activeSession: '{{ $firstSessionId }}',
    mobileMenuOpen: false,
    selectedSessionName: '{{ $firstSessionName }}'
}">
    @include('layouts.headers.intern')

    <main class="px-4 py-6 sm:px-6 max-w-7xl mx-auto flex-grow w-75">
        <h4 class="text-lg sm:text-xl font-semibold mb-2">Welcome {{ $intern->name }},</h4>
        <h5 class="text-base sm:text-lg text-center font-medium mb-6">Intern Dashboard</h5>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($complaints->isNotEmpty())
            <div class="bg-red-600 text-white font-bold p-4 rounded-lg mb-6">
                <p>You have {{ $complaints->count() }} pending complaint(s). Please resolve them. Please meet your supervisor</p>
            </div>
        @endif

        @if($allApproved2)
            <div class="mb-6 text-center">
                <a href="{{ route('certificate.download', ['reg_no' => $intern->reg_no]) }}"
                   class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded"
                   target="_blank">
                    🎓 Download Completion Certificate
                </a>
            </div>
        @else
            <div class="mb-6 text-center text-yellow-700 bg-yellow-100 border border-yellow-300 px-4 py-3 rounded">
                ⏳ Your sessions are currently under review. The certificate will be available after all approvals are complete.
            </div>
        @endif

        @foreach($iSessions as $index => $session)
            <div x-show="activeSession === '{{ $session->id }}'" x-transition>
                @php
                    $mySession = $internSessions->firstWhere('session_id', $session->id);
                @endphp

                @if(!$mySession)
                    <div class="bg-white p-6 rounded-lg shadow text-center">
                        <p class="text-red-600">Session is not available at this time. Please contact the System Admin.</p>
                    </div>
                @else
                    @php
                        $myComplaints = \App\Models\InternComplaint::where('intern_session_id', $mySession->id)
                            ->where('intern_reg_no', $intern->reg_no)
                            ->latest()
                            ->get();
                    @endphp

                    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow border-2 border-blue-900 mb-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="font-semibold text-blue-500">Supervisor</p>
                                <p>{{ $mySession->supervisor->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-500">Location</p>
                                <p>{{ $mySession->universityLocation->city ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-500">Speciality</p>
                                <p>{{ $intern->department_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-500">Project</p>
                                <p>{{ $mySession->project_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-500">File</p>
                                @if($mySession->project_path)
                                    <a href="{{ asset('storage/' . $mySession->project_path) }}" class="text-blue-600 underline" target="_blank">Download</a>
                                @else
                                    N/A
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-blue-500">Approval Status</p>
                                <ul class="text-sm space-y-1">
                                    <li>
                                        @if($mySession->is_approved)
                                            ✅ <span class="text-green-600 font-medium">Approved by Supervisor</span>
                                        @else
                                            ❌ <span class="text-yellow-600 font-medium">Pending Supervisor Approval</span>
                                        @endif
                                    </li>
                                    <li>
                                        @if($mySession->hod_approved)
                                            ✅ <span class="text-green-600 font-medium">Approved by HOD</span>
                                        @else
                                            ❌ <span class="text-yellow-600 font-medium">Pending HOD Approval</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Session Tasks</h3>

                        @if($mySession->tasks->isEmpty())
                            <p class="text-red-600">No tasks assigned yet for this session.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm border">
                                    <thead class="bg-blue-300">
                                        <tr>
                                            <th class="px-4 py-2 border text-left">Task No</th>
                                            <th class="px-4 py-2 border text-left">Task</th>
                                            <th class="px-4 py-2 border text-left">Rating</th>
                                            <th class="px-4 py-2 border text-left">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mySession->tasks as $task)
                                            <tr>
                                                <td class="px-4 py-2 border font-medium">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2 border font-medium">{{ $task->task_name }}</td>
                                                <td class="px-4 py-2 border">
                                                    @if($task->rating !== null)
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-green-600 font-semibold whitespace-nowrap">{{ $task->rating }}/5</span>
                                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($task->rating / 5) * 100 }}%"></div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-yellow-600">Not Rated</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border">{{ $task->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('intern_complaints.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="intern_session_id" value="{{ $mySession->id }}">
                        <label for="message" class="block font-semibold mb-1">Write your complaint</label>
                        <textarea name="message" required rows="4"
                                  class="w-full border border-gray-300 px-3 py-2 rounded-md focus:ring-blue-500"></textarea>

                        <button type="submit"
                                class="mt-2 bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded">
                            🚩 Submit Complaint
                        </button>
                    </form>

                    @if($myComplaints->count())
                        <div class="mt-6 bg-white p-4 border rounded shadow">
                            <h4 class="text-md font-semibold text-gray-800 mb-3">🗂 Your Complaints for this Session</h4>
                            <ul class="space-y-3">
                                @foreach($myComplaints as $c)
                                    <li class="border-l-4 pl-3 py-2 border-red-500 bg-red-50 text-gray-700">
                                        <div class="flex justify-between items-center">
                                            <p class="font-medium">{{ $c->message }}</p>
                                            <span class="text-sm font-semibold text-gray-500">({{ ucfirst($c->status) }})</span>
                                        </div>
                                        <small class="text-xs text-gray-400">{{ $c->created_at->format('d M Y, h:i A') }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </main>

    <footer class="bg-[#00204F] text-white text-center py-2 text-sm sm:text-base">
        © 2025 Sri Lanka Telecom IT - Digital Platforms, All rights reserved.
    </footer>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
