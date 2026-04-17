@extends('layouts.app')

@section('content')

@include('layouts.headers.intern')

<div class="max-w-6xl mx-auto mt-10 shadow-md rounded bg-gray-100 p-6 sm:p-10 border-2 border-blue-800">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">Intern Profile</h2>

    {{-- Profile Details divided into 2 columns on md+ screens --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 w-full text-gray-700 text-sm">

        {{-- Left Table --}}
        <div class="w-full">
            <table class="w-full text-sm text-gray-800 border border-gray-300">
                <tbody>
                    <tr class="border-b">
                        <td class="p-3 font-semibold w-1/2">Reg No:</td>
                        <td class="p-3">{{ $intern->reg_no }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Name:</td>
                        <td class="p-3">{{ $intern->name }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Certificate Name:</td>
                        <td class="p-3">{{ $intern->certificate_name }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Email:</td>
                        <td class="p-3">{{ $intern->email ?? 'N/A' }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Mobile:</td>
                        <td class="p-3">{{ $intern->mobile }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Right Table --}}
        <div class="w-full">
            <table class="w-full text-sm text-gray-800 border border-gray-300">
                <tbody>
                    <tr class="border-b">
                        <td class="p-3 font-semibold w-1/2">NIC:</td>
                        <td class="p-3">{{ $intern->nic }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Training Start:</td>
                        <td class="p-3">{{ $intern->training_start_date }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Training End:</td>
                        <td class="p-3">{{ $intern->training_end_date }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3 font-semibold">Description:</td>
                        <td class="p-3">{{ $intern->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="p-3 font-semibold">City:</td>
                        <td class="p-3">{{ $intern->city }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    {{-- Edit Profile Link --}}
    <div class="mt-8">
        <a href="{{ route('intern.profile.edit') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">
            Edit Profile
        </a>
    </div>

    {{-- Google Account Section --}}
    <div class="mt-10">
        @if (empty(auth()->user()->google_id))
            <h4 class="text-lg font-semibold text-red-900 mb-4">Connect Your Google Account</h4>
            <a href="{{ route('google.login', ['mode' => 'connect']) }}"
            class="bg-white text-gray-800 border border-gray-300 px-4 py-2 rounded inline-flex items-center gap-2 hover:shadow transition-all">
                <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s48-fcrop64=1,00000000ffffffff-rw" alt="Google logo" class="w-5 h-5">
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
