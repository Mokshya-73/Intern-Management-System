@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

        <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
">Add New Department</h3>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- University Selection Form -->
        <form method="GET" action="{{ route('admin.structure.departments.create') }}" class="mb-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Training School</label>
                <select name="university_id" id="university_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Choose Training School --</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ $selectedUniversity == $uni->id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Department Creation Form -->
        @if($selectedUniversity)
        <form action="{{ route('admin.structure.departments.store') }}" method="POST">
            @csrf

            <input type="hidden" name="university_id" value="{{ $selectedUniversity }}">

            <div class="grid grid-cols-1 gap-6">
                <!-- Location Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Location</label>
                    <select name="location_id" id="location_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Choose Location --</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                    <input type="text" name="name" id="name"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required placeholder="e.g. Department of Finance">
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Create Department
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
