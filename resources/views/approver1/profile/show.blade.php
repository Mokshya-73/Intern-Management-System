@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white border border-blue-800 rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4 text-center text-blue-900">My xsxProfile</h2>

    <div class="grid grid-cols-1 gap-4">
        <p><strong>Full Name:</strong> {{ $approver->name }}</p>
        <p><strong>Email:</strong> {{ $core->email }}</p>
        <p><strong>Designation:</strong> {{ $approver->designation }}</p>
        <p><strong>Description:</strong> {{ $approver->description }}</p>
    </div>

    <div class="mt-6 text-right">
        <a href="{{ route('approver1.profile.edit') }}" class="px-5 py-2 bg-blue-900 text-white rounded hover:bg-blue-700">
            Edit Profile
        </a>
    </div>
    {{-- Google Section --}}
        <div class="mt-10">
            @if (empty(auth()->user()->google_id))
                <h4 class="text-lg font-semibold text-red-900 mb-4">Connect Your Google Account</h4>
                <a href="{{ route('google.login', ['mode' => 'connect']) }}"
                   class="bg-white text-gray-800 border border-gray-300 px-4 py-2 rounded inline-flex items-center gap-2 hover:shadow transition-all">
                    <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s48" alt="Google logo" class="w-5 h-5">
                    Connect with Google
                </a>
            @else
                <h3 class="text-lg font-semibold text-green-800 mb-4">Google Account Connected</h3>
                <p class="text-sm text-gray-700 mb-4">Linked account: <strong>{{ auth()->user()->google_email }}</strong></p>
                <form action="{{ route('google.disconnect') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded hover:bg-red-700">
                        <i class="fas fa-unlink"></i> Disconnect Google
                    </button>
                </form>
            @endif
        </div>
</div>

@endsection
