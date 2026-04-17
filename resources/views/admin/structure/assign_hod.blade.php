@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h3 class="text-lg font-semibold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5">
        Assign HOD
    </h3>

    @if(session('success'))
        <div id="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.structure.assignHOD') }}" class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">University</label>
                <select name="university_id" class="text-sm w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="this.form.submit()">
                    <option value="">Select University</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ request('university_id') == $uni->id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <select name="location_id" class="text-sm w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">Select Location</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->city }} - {{ $loc->address }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="text-sm text-gray-600 mb-4">
        Departments: {{ count($departments) }} | HODs: {{ count($hods) }}
    </div>

    @if(request('university_id') && request('location_id'))
        @if(count($departments) == 0)
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                No departments available for the selected university and location.
            </div>
        @elseif(count($hods) == 0)
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                No HODs available for assignment.
            </div>
        @endif
    @endif

    @if(count($departments) && count($hods))
    <form method="POST" action="{{ route('admin.structure.assignHOD.store') }}">
        @csrf
        <input type="hidden" name="university_id" value="{{ request('university_id') }}">
        <input type="hidden" name="location_id" value="{{ request('location_id') }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" class="text-sm w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        @php
                            $hasHod = $dept->departmentHod()->where('is_active', 1)->exists();
                        @endphp
                        <option value="{{ $dept->id }}" {{ $hasHod ? 'disabled' : '' }}>
                            {{ $dept->name }} {{ $hasHod ? '(Already Assigned)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

           <div class="w-full px-2 sm:px-4 md:px-6 lg:px-8">
    <label class="block text-sm sm:text-base font-medium text-gray-700 mb-1">
        Select HOD
    </label>
    <select 
        name="hod_id" 
        class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
        required
    >
        <option value="">Select HOD</option>
        @foreach($hods as $hod)
            <option value="{{ $hod->id }}">
                {{ $hod->name }} ({{ $hod->reg_no }}) - {{ $hod->department }}
            </option>
        @endforeach
    </select>
</div>

        </div>

        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Assign HOD
            </button>
        </div>
    </form>
    @endif
</div>

<script>
    // Auto-hide success message after 3 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection
