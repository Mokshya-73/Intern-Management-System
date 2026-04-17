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

<div 
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

    <div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">
        <h2 class="text-xl font-bold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5">Edit Profile</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <form action="{{ route('supervisor.profile.update') }}" method="POST">
            @csrf

            {{-- Grid with 2 columns --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left Column --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $supervisor->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $supervisor->mobile) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', $supervisor->designation) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>
                </div>

                {{-- Right Column --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded" rows="5">{{ old('description', $supervisor->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">New Password (optional)</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded">
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
