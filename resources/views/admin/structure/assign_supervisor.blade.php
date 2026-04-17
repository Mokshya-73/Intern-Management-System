@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h3 class="text-xl sm:text-xl font-semibold bg-blue-50 border-2 border-blue-200 text-blue-900 px-4 py-2 rounded text-center mb-6">
        Assign Supervisors to HOD
    </h3>

    <!-- Success Message -->
    @if(session('success'))
        <div id="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    <!-- Step 1: Select University and Location -->
    <form method="GET" action="{{ route('admin.structure.assign_supervisor') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">University</label>
                <select name="university_id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="this.form.submit()">
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
                <select name="location_id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
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

    @if(request('university_id') && request('location_id'))
        @if(count($departments) == 0)
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm sm:text-base">
                No departments available for the selected university and location.
            </div>
        @else
            <!-- Step 2: Select Department -->
            <form method="GET" action="{{ route('admin.structure.assign_supervisor') }}" class="mb-6">
                <input type="hidden" name="university_id" value="{{ request('university_id') }}">
                <input type="hidden" name="location_id" value="{{ request('location_id') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required onchange="this.form.submit()">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif
    @endif

    @if(request('department_id'))
        @if(!$selectedHod)
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm sm:text-base">
                No active HOD available for the selected department.
            </div>
        @else
            @if(count($supervisors) == 0)
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm sm:text-base">
                    No supervisors available for assignment.
                </div>
            @else
                <!-- Assign Supervisors Form -->
                <form method="POST" action="{{ route('admin.structure.assign_supervisor.store') }}">
                    @csrf
                    <input type="hidden" name="university_id" value="{{ request('university_id') }}">
                    <input type="hidden" name="location_id" value="{{ request('location_id') }}">
                    <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                    <input type="hidden" name="hod_id" value="{{ $selectedHod->id }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selected HOD</label>
                        <div class="w-full px-3 py-2 border bg-gray-100 rounded-md text-gray-800">
                            {{ $selectedHod->name }} ({{ $selectedHod->reg_no }})
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Supervisors</label>
                        <select name="supervisor_ids[]" multiple class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-40" required>
                            @foreach($supervisors as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }} ({{ $sup->reg_no }})</option>
                            @endforeach
                        </select>
                    </div>

                    @if($assignedSupervisors->isNotEmpty())
                        <div class="mt-4 mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-1">Already Assigned Supervisors</h3>
                            <ul class="list-disc pl-6 space-y-1 text-gray-700 bg-gray-100 p-3 rounded-md text-sm">
                                @foreach($assignedSupervisors as $sup)
                                    <li>{{ $sup->name }} ({{ $sup->reg_no }}) - {{ $sup->designation }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Back
                        </a>

                        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            Assign Supervisors
                        </button>
                    </div>
                </form>
            @endif
        @endif
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => successMessage.style.display = 'none', 3000);
        }
    });
</script>
@endsection
