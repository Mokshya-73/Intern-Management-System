@extends('layouts.app')

@section('content')
@php
    use App\Models\Supervisor;
    use App\Models\UserCoreData;
    use App\Models\InternProfile;
    use App\Models\InternSession;

    $authUser = auth()->user();
    $supervisor = Supervisor::where('reg_no', $authUser->reg_no)->first();
    $core = UserCoreData::where('reg_no', $authUser->reg_no)->first();

    $internSessions = collect();
    if ($supervisor) {
        $internSessions = InternSession::with(['intern', 'tasks', 'session'])
            ->where('sup_id', $supervisor->id)
            ->get();
    }

    $wasIntern = InternProfile::where('reg_no', $authUser->reg_no)->exists();
@endphp

<div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col" 
     x-data="{
        mobileMenuOpen: false,
        statusFilter: window.location.hash === '#completed' ? 'completed' : 'ongoing',
        searchRegNo: '',
        showComplainModal: false,
        activeTab: window.location.hash === '#completed' ? 'completed' : 'ongoing'
     }">

    {{-- Top Navigation --}}
    <nav class="bg-[#00204F] text-white px-6 py-4 shadow">
        <div class="flex flex-wrap items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <img src="https://i.postimg.cc/2874YvsJ/logo.png" class="h-10" alt="Logo" />
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>

            <div :class="{'hidden md:flex': !mobileMenuOpen}" class="flex flex-col md:flex-row md:items-center gap-4 mt-4 md:mt-0">
                <a href="#" @click.prevent="activeTab = 'ongoing'; statusFilter = 'ongoing'"
                   :class="activeTab === 'ongoing' ? 'text-green-400 font-semibold' : 'hover:text-green-300'">Ongoing</a>
                <a href="#" @click.prevent="activeTab = 'completed'; statusFilter = 'completed'"
                   :class="activeTab === 'completed' ? 'text-green-400 font-semibold' : 'hover:text-green-300'">Completed</a>
                <a href="{{ route('supervisor.complaints.history') }}" @click="activeTab = 'complaints'"
                   :class="activeTab === 'complaints' ? 'text-green-400 font-semibold' : 'hover:text-green-300'">Complains</a>
                <a href="#" @click.prevent="activeTab = 'file'; showComplainModal = true"
                   :class="activeTab === 'file' ? 'text-green-400 font-semibold' : 'hover:text-green-300'">File a Complain</a>
                @if($wasIntern)
                    <a href="{{ route('supervisor.myInternship') }}" @click="activeTab = 'myInternship'"
                       :class="activeTab === 'myInternship' ? 'text-red-400 font-semibold' : 'hover:text-red-300'">My Internship</a>
                @endif
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-user text-2xl"></i>
                    <span>{{ $supervisor->name }}</span>
                    <i class="fa-solid fa-caret-down"></i>
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded shadow-lg z-50">
                    <a href="{{ route('supervisor.profile.show') }}" class="block px-4 py-2 hover:bg-blue-100">Profile <i class="fa-solid fa-gear float-right"></i></a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="block w-full text-left px-4 py-2 text-red-600 hover:bg-blue-100">
                            Logout <i class="fa-solid fa-right-from-bracket float-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content Section --}}
    <main class="flex-grow py-10 px-6 md:px-12 lg:px-20">
<div class="relative max-w-7xl mx-auto p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-bold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-10">
        My Profile
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-20 text-gray-700 text-base">
        <div class="space-y-4">
            <p><strong class="text-gray-900">Full Name:</strong> {{ $supervisor->name }}</p>
            <p><strong class="text-gray-900">Mobile:</strong> {{ $supervisor->mobile }}</p>
            <p><strong class="text-gray-900">Email:</strong> {{ $core->email }}</p>
        </div>
        <div class="space-y-4">
            <p><strong class="text-gray-900">Designation:</strong> {{ $supervisor->designation }}</p>
            <p><strong class="text-gray-900">Description:</strong> {{ $supervisor->description }}</p>
        </div>
    </div>

    {{-- Edit Button Positioned bottom right inside the card --}}
    <div class="absolute bottom-6 right-6">
        <a href="{{ route('supervisor.profile.edit') }}"
           class="inline-block bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Edit Profile
        </a>
    </div>








        {{-- Google Account Section --}}
        <div >
            @if (empty(auth()->user()->google_id))
                <h4 class="text-lg font-semibold text-black-900 mb-4">Connect Your Google Account</h4>
                <a href="{{ route('google.login', ['mode' => 'connect']) }}"
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 px-4 py-2 rounded hover:shadow">
                    <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s48" class="w-5 h-5" alt="Google">
                    Connect with Google
                </a>
            @else
                <h4 class="text-lg font-semibold text-green-800 mb-2">Google Account Connected</h4>
                <p class="text-sm text-gray-700 mb-4">
                    Linked account: <strong>{{ auth()->user()->google_email }}</strong>
                </p>
                <form action="{{ route('google.disconnect') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="bg-red-800 text-white px-4 py-2 rounded hover:bg-red-700 inline-flex items-center gap-2">
                        <i class="fas fa-unlink"></i> Disconnect Google Account
                    </button>
                </form>
            @endif
        </div>
    </main>
</div>
@endsection
