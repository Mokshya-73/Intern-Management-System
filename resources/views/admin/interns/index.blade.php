@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">
    <h2 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>
        Interns Management
    </h2>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.interns.create') }}" 
       class="block sm:inline-block bg-blue-900 text-white px-4 py-2 rounded mb-6 hover:bg-blue-700 w-full sm:w-auto text-center">
       Add New Intern
    </a>

    {{-- ------------------- Ongoing Interns ------------------- --}}
    <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-3">Ongoing Interns</h3>
    <div class="w-full overflow-x-auto mb-10">
        <table class="w-full min-w-[768px] bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="px-2 py-2 border text-center">Reg No</th>
                    <th class="px-2 py-2 border text-center">Name</th>
                    <th class="px-2 py-2 border text-center">Certificate Name</th>
                    <th class="px-2 py-2 border text-center">Email</th>
                    <th class="px-2 py-2 border text-center">Mobile</th>
                    <th class="px-2 py-2 border text-center">City</th>
                    <th class="px-2 py-2 border text-center">Start Date</th>
                    <th class="px-2 py-2 border text-center">End Date</th>
                    <th class="px-2 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ongoingInterns as $intern)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 py-2 border text-center">{{ $intern->reg_no }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->name }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->certificate_name }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->email }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->mobile }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->city }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->training_start_date }}</td>
                        <td class="px-2 py-2 border text-center">{{ $intern->training_end_date }}</td>
                        <td class="px-2 py-2 border text-center">
                            <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center gap-2">
                                <a href="{{ route('admin.interns.edit', $intern->reg_no) }}" 
                                   class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 text-center">
                                   Edit
                                </a>

                                <form action="{{ route('admin.interns.delete', $intern->reg_no) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure?')" 
                                            class="bg-red-800 text-white px-3 py-1 rounded text-xs hover:bg-red-700 w-full text-center">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-red-500">No ongoing interns found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ------------------- Completed Interns ------------------- --}}
    <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-3">Completed Interns</h3>
    <div class="w-full overflow-x-auto">
        <table class="w-full min-w-[768px] bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="px-2 py-2 border text-left">Reg No</th>
                    <th class="px-2 py-2 border text-left">Name</th>
                    <th class="px-2 py-2 border text-left">Certificate Name</th>
                    <th class="px-2 py-2 border text-left">Email</th>
                    <th class="px-2 py-2 border text-left">Mobile</th>
                    <th class="px-2 py-2 border text-left">City</th>
                    <th class="px-2 py-2 border text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($previousInterns as $intern)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 py-2 border">{{ $intern->reg_no }}</td>
                        <td class="px-2 py-2 border">{{ $intern->name }}</td>
                        <td class="px-2 py-2 border">{{ $intern->certificate_name }}</td>
                        <td class="px-2 py-2 border">{{ $intern->email }}</td>
                        <td class="px-2 py-2 border">{{ $intern->mobile }}</td>
                        <td class="px-2 py-2 border">{{ $intern->city }}</td>
                        <td class="px-2 py-2 border text-gray-600">{{ $intern->status ?? 'Inactive' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-red-500">No previous interns found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors mt-4 w-max">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" 
                      d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" 
                      clip-rule="evenodd" />
            </svg>
            Back
        </a>
    </div>
</div>
@endsection
