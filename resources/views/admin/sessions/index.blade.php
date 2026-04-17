@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">
    <h2 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>
        All Assigned Intern Sessions
    </h2>

    @if(session('success'))
        <div class="mb-4 p-3 sm:p-4 bg-green-100 text-green-700 rounded text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 sm:p-4 bg-red-100 text-red-700 rounded text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="px-2 sm:px-4 py-2 border text-center">Intern Reg No</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Session</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Location</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Supervisor</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Approval</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Assigned On</th>
                    <th class="px-2 sm:px-4 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $s)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 sm:px-4 py-2 border text-center">{{ $s->reg_no }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center">{{ $s->session->session_name ?? '-' }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center">
                            {{ $s->universityLocation->city ?? 'N/A' }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 border text-center">{{ $s->supervisor->name ?? '-' }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center">
                            @if($s->is_approved)
                                <span class="text-green-600 font-semibold">Approved</span>
                            @else
                                <span class="text-red-600">Pending</span>
                            @endif
                        </td>
                        <td class="px-2 sm:px-4 py-2 border text-center">{{ $s->created_at->format('Y-m-d') }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center flex flex-col sm:flex-row justify-center items-center gap-1">
                            @if(!$s->is_approved)
                                <a href="{{ route('admin.sessions.edit', $s->id) }}" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded transition duration-200">
                                    Edit
                                </a>
                                <form action="{{ route('admin.sessions.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-900 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition duration-200">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500 text-xs italic">Locked</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-red-500 text-sm sm:text-base">
                            No sessions assigned yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back
    </a>
</div>
@endsection
