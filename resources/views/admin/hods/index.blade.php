@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>
        Head of Departments (HODs)
    </h2>

    <a href="{{ route('admin.hods.create') }}"  
       class="block sm:inline-block bg-blue-900 text-white px-4 py-2 rounded mb-6 hover:bg-blue-700 text-center w-full sm:w-auto">
        Add New HOD
    </a>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if($hods->isEmpty())
        <p class="text-red-600 text-sm sm:text-base">No HODs found.</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="border px-2 sm:px-4 py-2">Reg No</th>
                    <th class="border px-2 sm:px-4 py-2">Name</th>
                    <th class="border px-2 sm:px-4 py-2">Email</th>
                    <th class="border px-2 sm:px-4 py-2">Department</th>
                    <th class="border px-2 sm:px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hods as $hod)
                    <tr class="hover:bg-gray-50 border-t">
                        <td class="border px-2 sm:px-4 py-2">{{ $hod->reg_no }}</td>
                        <td class="border px-2 sm:px-4 py-2">{{ $hod->name ?? 'No Name' }}</td>
                        <td class="border px-2 sm:px-4 py-2">{{ $hod->userCoreData->email ?? 'No Email' }}</td>
                        <td class="border px-2 sm:px-4 py-2">{{ $hod->department ?? 'No Department' }}</td>
                        <td class="border px-2 sm:px-4 py-2">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <a href="{{ route('admin.hods.edit', $hod->id) }}" 
                                   class="bg-green-600 text-white px-3 py-1 rounded text-xs sm:text-sm text-center hover:bg-green-700">
                                    Edit
                                </a>
                                <form action="{{ route('admin.hods.delete', $hod->reg_no) }}" 
                                      method="POST" 
                                      class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete this HOD?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-800 text-white px-3 py-1 rounded text-xs sm:text-sm hover:bg-red-700 w-full sm:w-auto">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors mt-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back
        </a>
    </div>
    @endif
</div>
@endsection
