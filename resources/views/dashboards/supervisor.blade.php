@extends('layouts.app')

@section('content')
@php
    $supervisor = \App\Models\Supervisor::where('reg_no', auth()->user()->reg_no)->first();

    $internSessions = collect();
    if ($supervisor) {
        $internSessions = \App\Models\InternSession::with(['intern', 'tasks', 'session'])
            ->where('sup_id', $supervisor->id)
            ->get();
    }
@endphp

<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800"
     x-data="{
        mobileMenuOpen: false,
        statusFilter: window.location.hash === '#completed' ? 'completed' : 'ongoing',
        searchRegNo: '',
        showComplainModal: false,
        activeTab: window.location.hash === '#completed' ? 'completed' : 'ongoing'
     }">

    <!-- Navigation Bar -->
    <nav class="bg-[#00204F] text-white px-4 py-3">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="https://i.postimg.cc/2874YvsJ/logo.png" alt="Logo" class="h-10" />
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>

            <div :class="{'hidden md:block': !mobileMenuOpen, 'block': mobileMenuOpen}" class="w-full md:w-auto">
                <ul class="flex flex-col md:flex-row items-center gap-4 text-sm md:text-base py-4 md:py-0">
                    <li>
                        <a href="#"
                        @click.prevent="activeTab = 'ongoing'; statusFilter = 'ongoing'"
                        :class="activeTab === 'ongoing'
                                ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
                                : 'hover:text-green-400'">
                            Ongoing
                        </a>
                    </li>

                    <li>
                        <a href="#"
                        @click.prevent="activeTab = 'completed'; statusFilter = 'completed'"
                        :class="activeTab === 'completed'
                                ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
                                : 'hover:text-green-400'">
                            Completed
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('supervisor.complaints.history') }}"
                        :class="activeTab === 'complaints'
                                ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
                                : 'hover:text-green-400'"
                        @click="activeTab = 'complaints'">
                            Complains
                        </a>
                    </li>

                    <li>
                        <a href="#"
                        @click.prevent="activeTab = 'file'; showComplainModal = true"
                        :class="activeTab === 'file'
                                ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
                                : 'hover:text-green-400'">
                            File a Complain
                        </a>
                    </li>
                    @php
    $regNo = auth()->user()->reg_no;
    $wasIntern = \App\Models\InternProfile::where('reg_no', $regNo)->exists();
@endphp

@if($wasIntern)
    <li>
        <a href="{{ route('supervisor.myInternship') }}"
            :class="activeTab === 'myInternship'
                        ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
                        : 'hover:text-red-400'"
            @click="activeTab = 'myInternship'">
            My Internship
        </a>
    </li>
@endif

                </ul>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded hover:text-green-400">
                    <i class="fa-solid fa-circle-user text-2xl"></i>
                    <span>{{ $supervisor->name }}</span>
                    <i class="fa-solid fa-caret-down"></i>
                </button>

                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md z-50">
                    <a href="{{ route('supervisor.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">

                        Profile<i class="fa-solid fa-gear pl-2"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-100">
                            Logout<i class="fa-solid fa-right-from-bracket pl-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="px-4 py-6 sm:px-6 max-w-7xl mx-auto flex-grow">
        <h4 class="text-lg sm:text-xl font-semibold mb-2">Welcome Supervisor,</h4>
        <h5 class="text-base sm:text-lg text-center font-normal mb-6">Intern Session Dashboard</h5>

        <!-- Filter Tabs -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-300">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text"
                    x-model="searchRegNo"
                    @input="searchRegNo = searchRegNo.replace(/\D/g, '').slice(0, 5)"
                    placeholder="Search by Intern ID"
                    class="pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-4 w-full focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
            </div>
        </div>

        <!-- Intern Table -->
        @if($internSessions->count())
            <table class="min-w-full bg-white border text-sm ">
                <thead class="bg-blue-300 ">
                    <tr>
                        <th class="px-4 py-2 border text-left">Intern ID</th>
                        <th class="px-4 py-2 border text-left">Name</th>
                        <th class="px-4 py-2 border text-left">Project</th>
                        <th class="px-4 py-2 border text-left">Session</th>
                        <th class="px-4 py-2 border text-left">Status</th>
                        <th class="px-4 py-2 border text-left">Tasks</th>
                        <th class="px-4 py-2 border text-left align-top">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($internSessions as $item)
                          <tr x-show="(
                (activeTab === 'ongoing' && {{ $item->is_approved }} == 0) ||
                (activeTab === 'completed' && {{ $item->is_approved }} == 1)
            ) && (
                searchRegNo === '' ||
                '{{ $item->intern->reg_no }}'.toLowerCase().includes(searchRegNo.toLowerCase())
            )"
        >
                            <td class="px-4 py-2 border">{{ $item->intern->reg_no }}</td>
                            <td class="px-4 py-2 border">{{ $item->intern->name }}</td>
                            <td class="px-4 py-2 border">{{ $item->project_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">
                                {{ $item->session->session_name ?? 'N/A' }} ({{ $item->session->session_time_period ?? 'N/A' }})
                            </td>
                            <td class="px-4 py-2 border">
                                @if($item->is_approved)
                                    <span class="text-green-600 font-semibold">Completed</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Ongoing</span>
                                @endif
                            </td>

                                        <td class="px-4 py-2 border">
                    @if($item->tasks->isEmpty())
                        <p class="text-gray-500">No tasks assigned</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border-collapse">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-2 py-1 border-b border-gray-200 text-left">#</th>
                                        <th class="px-2 py-1 border-b border-gray-200 text-left">Task</th>
                                        <th class="px-2 py-1 border-b border-gray-200 text-left">Rating</th>
                                        <th class="px-2 py-1 border-b border-gray-200 text-left">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->tasks as $key => $task)
                                        <tr class="{{ $loop->last ? '' : 'border-b border-gray-200' }}">
                                            <td class="px-2 py-1">{{ $loop->iteration }}</td>
                                            <td class="px-2 py-1">{{ $task->task_name }}</td>
                                            <td class="px-2 py-1">{{ $task->rating ?? 'N/A' }}</td>
                                            <td class="px-2 py-1">{{ $task->description ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </td>
                 <td class="px-4 py-2 border text-left align-top">
                                @if(!$item->is_approved)
                                    <form method="POST" action="{{ route('supervisor.approve', $item->id) }}" class="mb-2">
                                        @csrf
                                        <button type="submit" class="mt-1 inline-block bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                            Approve
                                        </button>
                                    </form>
                                @else
                                    <span class="text-green-700 text-sm">Approved</span>
                                @endif

                                <a href="{{ route('supervisor.review', $item->id) }}" class="mt-1 inline-block bg-blue-900 text-white px-3 py-1 rounded text-xs hover:bg-blue-800">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-gray-500 py-6">No intern sessions found.</p>
        @endif
<!-- Complaint Modal -->
<div x-show="showComplainModal" x-transition
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div @click.away="showComplainModal = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 border-2 border-red-500">

        <h2 class="text-lg font-semibold mb-4">File a Complaint</h2>

        <form action="{{ route('supervisor.complaints.store') }}" method="POST">
            @csrf

            <div class="mb-4">
    <label for="intern_reg_no" class="block font-semibold mb-1">Select Assigned Intern:</label>
    <select name="intern_reg_no" id="intern_reg_no" class="w-full border rounded px-3 py-2" required>
        <option value="">-- Select Intern --</option>
        @foreach($internSessions as $item)
            <option value="{{ $item->intern->reg_no }}">
                {{ $item->intern->reg_no }} -{{ $item->intern->name }} ( Session {{ $item->session_id }})
            </option>
        @endforeach
    </select>
</div>


            <div class="mb-4">
                <label for="complaint" class="block font-semibold mb-1">Complaint:</label>
                <textarea name="complaint" id="complaint" rows="4" class="w-full border rounded px-3 py-2 text-red-500" required></textarea>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 rounded bg-blue-900 text-white hover:bg-blue-800 text-sm">
                    Submit
                </button>
                <button type="button" @click="showComplainModal = false"
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-sm">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

    </main>

    <footer class="bg-[#00204F] text-white text-center py-2 text-sm sm:text-base">
        © 2025 Sri Lanka Telecom IT - Digital Platforms, All rights reserved.
    </footer>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
