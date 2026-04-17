@extends('layouts.app')

@section('content')
@php
    $approver1 = \App\Models\Approver1::where('reg_no', auth()->user()->reg_no)->first();
    $internsData = collect($internSessionsByRegNo)->map(fn($s) => $s->first()->intern)->values();
@endphp

<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800"
     x-data="{
        search: '',
        selected: '{{ request()->reg }}',
        open: false,
        showSuccess: @if(session('success')) true @else false @endif,
        interns: {{ Js::from($internsData) }},
        get filtered() {
            return this.interns.filter(i =>
                i.reg_no.toLowerCase().includes(this.search.toLowerCase()) ||
                i.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        init() {
            if (this.showSuccess) {
                setTimeout(() => this.showSuccess = false, 3000);
            }

            if (this.selected) {
                const intern = this.interns.find(i => i.reg_no === this.selected);
                this.search = intern ? `${intern.name} (${intern.reg_no})` : '';
            }
        }
    }"
    >

    <main class="px-4 py-6 sm:px-6 max-w-7xl mx-auto flex-grow">
        <!-- Title -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-white bg-blue-900 px-4 py-2 rounded-md text-center">Approver 1 Dashboard</h3>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
    <div class="mb-4 bg-green-100 text-green-800 border border-green-400 px-4 py-2 rounded">
        {{ session('success') }}
    </div>
@endif


        {{-- Custom Searchable Dropdown --}}
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search & Select Intern</label>

            <div class="relative">
                <input
                    type="text"
                    placeholder="Search by Reg No or Name..."
                    x-model="search"
                    @focus="open = true"
                    @click.away="open = false"
                    class="w-full px-3 py-2 border border-gray-300 rounded shadow focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <ul x-show="open && filtered.length"
                    class="absolute z-50 bg-white border border-gray-300 mt-1 rounded shadow w-full max-h-60 overflow-auto">
                    <template x-for="intern in filtered" :key="intern.reg_no">
                        <li @click="selected = intern.reg_no; search = intern.name + ' (' + intern.reg_no + ')'; open = false"
                            class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-sm">
                            <span x-text="intern.name + ' (' + intern.reg_no + ')'"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Intern Session View --}}
        @foreach($internSessionsByRegNo as $regNo => $internSessions)
            <div x-show="selected === '{{ $regNo }}'" x-transition class="mb-10">
                @php $intern = $internSessions->first()->intern; @endphp

                <div class="bg-white rounded shadow border-2 border-blue-900 p-6">
                    <h3 class="text-xl font-bold text-blue-700 mb-4">{{ $intern->name }} ({{ $intern->reg_no }})</h3>
                    <div class="flex justify-end gap-4 mb-4">

</div>

                    @foreach($internSessions->sortBy('session_id') as $session)
                        <div class="bg-gray-50 border border-gray-300 rounded p-4 mb-6">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                <div>
                                    <p><strong>Session:</strong> {{ $session->session->session_name }}</p>
                                    <p><strong>Supervisor Approval:</strong>
                                        {!! $session->is_approved ? '<span class="text-green-600">✅</span>' : '<span class="text-red-600">❌</span>' !!}
                                    </p>
                                    <p><strong>HOD Approval:</strong>
                                        {!! $session->hod_approved ? '<span class="text-green-600">✅</span>' : '<span class="text-red-600">❌</span>' !!}
                                    </p>
                                    <p><strong>Approver 1:</strong>
                                        {!! $session->approver1_approved ? '<span class="text-green-600 font-semibold">Approved</span>' : '<span class="text-yellow-600 font-semibold">Pending</span>' !!}
                                    </p>
                                </div>

                                <div class="mt-4 md:mt-0">
                                    @php $canApprove = $session->is_approved && $session->hod_approved; @endphp

                                    @if(!$session->approver1_approved)
                                        @if($canApprove)
                                            <div x-data="{
    session: { approved: {{ $session->approver1_approved ? 'true' : 'false' }} },
    loading: false,
    approve() {
        this.loading = true;
        fetch('{{ route('approver1.approve', $session->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) throw new Error();
            return response.json();
        })
        .then(() => {
            this.session.approved = true;
            this.loading = false;
        })
        .catch(() => {
            alert('Approval failed. Check console.');
            this.loading = false;
        });
    }
}">
    <template x-if="!session.approved">
        <button @click="approve" x-bind:disabled="loading"
            class="bg-green-600 text-white px-4 py-1 rounded shadow hover:bg-green-700 text-sm">
            <span x-show="!loading">Approve Session</span>
            <span x-show="loading">Approving...</span>
        </button>
    </template>
    <template x-if="session.approved">
        <span class="text-gray-500 text-sm">✅ Already Approved</span>
    </template>
</div>


                                        @else
                                            <span class="text-sm text-red-600">Supervisor & HOD approval required</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 text-sm">✅ Already Approved</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tasks --}}
                            <div class="mt-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Tasks</h4>

                                @if($session->tasks->isEmpty())
                                    <p class="text-sm text-red-600">No tasks assigned yet.</p>
                                @else
                                    <table class="min-w-full text-sm border bg-white">
                                        <thead class="bg-blue-200 text-left">
                                            <tr>
                                                <th class="px-4 py-2 border">#</th>
                                                <th class="px-4 py-2 border">Task Name</th>
                                                <th class="px-4 py-2 border">Rating</th>
                                                <th class="px-4 py-2 border">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($session->tasks as $task)
                                                <tr>
                                                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                                    <td class="px-4 py-2 border">{{ $task->task_name }}</td>
                                                    <td class="px-4 py-2 border">
                                                        @if($task->rating !== null)
                                                            <span class="text-green-700 font-semibold">{{ $task->rating }}/5</span>
                                                        @else
                                                            <span class="text-yellow-600">Not Rated</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 border">{{ $task->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </main>

    <footer class="bg-[#00204F] text-white text-center py-2 text-sm sm:text-base">
        © 2025 Sri Lanka Telecom IT - Digital Platforms, All rights reserved.
    </footer>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
