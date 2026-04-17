@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2 class="text-xl font-semibold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center">
        Edit Intern Session
    </h2>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
        <strong>Errors:</strong>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.sessions.update', $session->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div class="space-y-6">

                <!-- Intern Reg No -->
                <div>
                    <label class="block font-medium text-gray-700">Intern Reg No</label>
                    <input type="text" value="{{ $session->reg_no }}" class="w-full mt-1 p-2 border rounded bg-gray-100" disabled>
                </div>

                <!-- Session -->
                <div>
                    <label class="block font-medium text-gray-700">Session</label>
                    <select name="session_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($sessions as $sess)
                        <option value="{{ $sess->id }}" {{ $sess->id == $session->session_id ? 'selected' : '' }}>
                            {{ $sess->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supervisor -->
                <div>
                    <label class="block font-medium text-gray-700">Supervisor</label>
                    <select name="sup_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($supervisors as $sup)
                        <option value="{{ $sup->id }}" {{ $sup->id == $session->sup_id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block font-medium text-gray-700">Location</label>
                    <input type="text" name="location" class="w-full mt-1 p-2 border rounded" value="{{ $session->location }}" required>
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6">

                <!-- University -->
                <div>
                    <label class="block font-medium text-gray-700">University</label>
                    <select name="uni_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ $uni->id == $session->uni_id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block font-medium text-gray-700">Department</label>
                    <select name="department_id" class="w-full mt-1 p-2 border rounded">
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $dept->id == $session->department_id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Name -->
                <div>
                    <label class="block font-medium text-gray-700">Project Name (optional)</label>
                    <input type="text" name="project_name" class="w-full mt-1 p-2 border rounded" value="{{ $session->project_name }}">
                </div>

                <!-- Upload Project File -->
                <div>
                    <label class="block font-medium text-gray-700">Upload Project File (optional)</label>
                    <input type="file" name="project_path" class="mt-1">
                    @if ($session->project_path)
                    <p class="text-sm text-green-700 mt-1">Current File: 
                        <a href="{{ asset('storage/' . $session->project_path) }}" class="underline" target="_blank">View</a>
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit and Cancel Buttons -->
       
          
           <div class="flex justify-between items-center pt-4">
                <a href="{{ route('admin.interns.index') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>

                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Update Intern
                </button>
            </div>
        
    </form>
</div>
@endsection
