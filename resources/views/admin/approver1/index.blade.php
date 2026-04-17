@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>
        Approver 1 List
    </h2>

    <a href="{{ route('admin.approver2.create') }}" 
       class="block sm:inline-block bg-blue-900 text-white px-4 py-2 rounded mb-6 hover:bg-blue-700 w-full sm:w-auto text-center">
        Add New Approver 1
    </a>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto mb-10">
        <table class="min-w-full bg-white border text-xs sm:text-sm">
            <thead class="bg-blue-300">
                <tr>
                    <th class="px-2 py-2 border text-center">Reg No</th>
                    <th class="px-2 py-2 border text-center">Name</th>
                    <th class="px-2 py-2 border text-center">Designation</th>
                    <th class="px-2 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvers as $approver)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 py-2 border text-left">{{ $approver->reg_no }}</td>
                        <td class="px-2 py-2 border text-left">{{ $approver->name }}</td>
                        <td class="px-2 py-2 border text-left">{{ $approver->designation }}</td>
                        <td class="px-2 py-2 border text-center">
                            <div class="flex flex-col sm:flex-row sm:justify-center sm:space-x-2 space-y-2 sm:space-y-0">
                                <a href="{{ route('admin.approver1.edit', $approver->reg_no) }}"
                                   class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 text-center">
                                    Edit
                                </a>
                                <form action="{{ route('admin.approver1.delete', $approver->reg_no) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure?')" 
                                            class="bg-red-800 text-white px-3 py-1 rounded text-xs hover:bg-red-700 w-full">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                  clip-rule="evenodd" />
        </svg>
        Back
    </a>
</div>
@endsection
