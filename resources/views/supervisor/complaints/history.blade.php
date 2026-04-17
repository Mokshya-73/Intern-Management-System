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
<div x-data="{ 
    showComplainModal: false, 
    mobileMenuOpen: false,
    activeTab: 'complaints' // Initialize with complaints tab active
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
    <a href="{{ route('supervisor.dashboard') }}#ongoing"
       @click="activeTab = 'ongoing'; statusFilter = 'ongoing'"
       :class="activeTab === 'ongoing'
               ? 'relative after:block after:h-1 after:bg-[#6cbf3c] after:w-3/4 after:mx-auto after:rounded-full font-medium'
               : 'hover:text-green-400'">
        Ongoing
    </a>
</li>

<li>
    <a href="{{ route('supervisor.dashboard') }}#completed"
       @click="activeTab = 'completed'; statusFilter = 'completed'"
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
                </ul>
            </div>
            
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded hover:text-green-400">
                    <i class="fa-solid fa-circle-user text-2xl"></i>
                    <span>{{ $supervisor->name }}</span>
                    <i class="fa-solid fa-caret-down"></i>
                </button>

                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
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

<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-xl font-semibold mb-4">Complaint History</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($complaints->isEmpty())
        <p>No complaints submitted yet.</p>
    @else
        <table class="w-full border-collapse bg-white text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Complaint</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $complaint)
                    <tr>
                        <td class="p-2 border">{{ $complaint->created_at->format('Y-m-d') }}</td>
                        <td class="p-2 border">{{ $complaint->intern_reg_no }}</td>
                        <td class="p-2 border">{{ $complaint->intern->name ?? '-' }}</td>
                        <td class="p-2 border">{{ $complaint->complaint }}</td>
                        <td class="p-2 border">
                            @if($complaint->status == 'resolved')
                                <span class="text-green-600 font-semibold">Resolved</span>
                            @else
                                <span class="text-red-600 font-semibold">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
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
                            {{ $item->intern->name }} ({{ $item->intern->reg_no }})
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
<footer class="bg-[#00204F] text-white text-center py-2 text-sm sm:text-base fixed bottom-0 left-0 right-0">
    © 2025 Sri Lanka Telecom IT - Digital Platforms, All rights reserved.
</footer>
</div>

<<script src="//unpkg.com/alpinejs" defer></script>

@endsection
