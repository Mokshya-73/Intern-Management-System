@extends('layouts.app')

@section('content')
<div class="relative max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-bold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5">
        My Profile
    </h2>

    <div class="grid grid-cols-1 gap-4">
        <p><strong>Full Name:</strong> {{ $hod->name }}</p>
        <p><strong>Email:</strong> {{ $core->email }}</p>
        <p><strong>Description:</strong> {{ $hod->description }}</p>
    </div>

    {{-- Button placed bottom right --}}
    <div class="absolute bottom-4 right-4">
        <a href="{{ route('hod.profile.edit') }}" class="px-5 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">
            Edit Profile
        </a>
    </div>

    {{-- Google Account Section --}}
    <div class="mt-10">
        @if (empty(auth()->user()->google_id))
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Connect Your Google Account</h4>
            <a href="{{ route('google.login', ['mode' => 'connect']) }}"
               class="bg-white text-gray-800 border border-gray-300 px-4 py-2 rounded inline-flex items-center gap-2 hover:shadow transition-all">
                <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s48-fcrop64=1,00000000ffffffff-rw" 
                     alt="Google logo" class="w-5 h-5">
                Connect with Google
            </a>
        @else
            <h3 class="text-lg font-semibold text-green-800 mb-4">Google Account Connected</h3>
            <p class="text-sm text-gray-700 mb-4">
                Your Google account is linked: <strong>{{ auth()->user()->google_email }}</strong>
            </p>
            <form action="{{ route('google.disconnect') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded inline-flex items-center gap-2 hover:bg-red-700">
                    <i class="fas fa-unlink"></i> Disconnect Google Account
                </button>
            </form>
        @endif
    </div>

</div>
@endsection
