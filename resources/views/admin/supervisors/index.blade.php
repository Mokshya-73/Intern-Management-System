@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

        <h2 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>All Supervisors</h2>
        <a href="{{ route('admin.supervisors.create') }}" class="block sm:inline-block bg-blue-900 text-white px-4 py-2 rounded mb-6 hover:bg-blue-700 w-full sm:w-auto text-center">Add New Supervisor</a>
        
    

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

<div class="w-full overflow-x-auto mb-10">
        <table class="w-full min-w-[768px] bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="border px-4 py-2 text-left">Reg No</th>
                    <th class="border px-4 py-2 text-left">Name</th>
                    <th class="border px-4 py-2 text-left">Email</th>
                    <th class="border px-4 py-2 text-left">University</th>
                    <th class="border px-4 py-2 text-left">Designation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supervisors as $supervisor)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $supervisor->reg_no }}</td>
                        <td class="border px-4 py-2">{{ $supervisor->name }}</td>
                        <td class="border px-4 py-2">{{ $supervisor->core->email ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $supervisor->university }}</td>
                        <td class="border px-4 py-2">{{ $supervisor->designation }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">No supervisors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
