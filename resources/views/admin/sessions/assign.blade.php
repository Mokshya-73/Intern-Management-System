@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 text-center">Assign Intern Session</h2>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif
            <!-- Intern Search -->
            <form method="GET" action="{{ route('admin.sessions.create') }}" class="mb-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center">Search Intern</h3>
                    
                    @if($errors->has('reg_no'))
                    <div class=" text-sm mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                        <p>{{ $errors->first('reg_no') }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name or Reg No</label>
                        <div class="flex gap-2">
                            <input type="text" name="reg_no" value="{{ request('reg_no') }}" placeholder="e.g. John or IT1234"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reg_no')  @enderror"
                                required>
                            <button type="submit" class="px-4 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                Search
                            </button>
                        </div>
                       
                    </div>
                </div>
            </form>

        <!-- Show form only if intern is selected -->
        @isset($intern)
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-white bg-blue-900 px-4 py-2 rounded-md">
                Assign Session to {{ $intern->name }} ({{ $intern->reg_no }})
            </h3>

            @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border-l-4  text-red-700 rounded">
                <h3 class="font-bold mb-1">Please fix these errors:</h3>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.sessions.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="reg_no" value="{{ $intern->reg_no }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Session Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Session</label>
                        <select name="session_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('session_id')  @enderror">
                            <option value="" disabled selected hidden>📅 Choose a Session</option>
                            @foreach($sessions as $s)
                                <option value="{{ $s->id }}">{{ $s->session_name }} ({{ $s->session_time_period }})</option>
                            @endforeach
                        </select>
                        @error('session_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>

                    </div>

                    <!-- University -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Training School</label>
                        <select name="uni_id" id="uniSelect" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('uni_id')  @enderror">
                            <option value="" disabled selected hidden>🏫 Choose a Training School</option>
                            @foreach($universities as $uni)
                                <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                            @endforeach
                        </select>
                        @error('uni_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <select name="location" id="locationSelect" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror">
                            <option value="" disabled selected hidden>📍 Select a Location</option>
                        </select>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" id="deptSelect" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department_id') border-red-500 @enderror">
                            <option value="" disabled selected hidden>🏢 Select Department</option>
                        </select>
                        @error('department_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supervisor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supervisor</label>
                        <select name="sup_id" id="supervisorSelect" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sup_id') border-red-500 @enderror">
                            <option value="" disabled selected hidden>🧑‍💼 Pick a Supervisor</option>
                        </select>
                        @error('sup_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Project Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                        <input type="text" name="project_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project_name')  @enderror">
                        @error('project_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project File -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Project File (PDF/ZIP)</label>
                        <input type="file" name="project_path" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project_path')  @enderror">
                        @error('project_path')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                      </a>

                    <div class="flex gap-4">
                        <button type="reset" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors border border-blue-600 hover:border-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Clear Form
                        </button>

                        <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                            </svg>
                            Assign Session
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endisset
    </div>
</div>
<script>
    const uniSelect = document.getElementById('uniSelect');
    const locationSelect = document.getElementById('locationSelect');
    const deptSelect = document.getElementById('deptSelect');
    const supervisorSelect = document.getElementById('supervisorSelect');


    uniSelect.addEventListener('change', function () {
        const universityId = this.value;

        locationSelect.innerHTML = `<option value="">-- Select Location --</option>`;
        deptSelect.innerHTML = `<option value="">-- Select Department --</option>`;

        if (universityId) {
            fetch(`/admin/get-locations/${universityId}`)
                .then(response => response.json())
                .then(locations => {
                    locations.forEach(loc => {
                        const option = document.createElement('option');
                        option.value = loc.id;              // ✅ storing location_id
                        option.textContent = loc.city;      // ✅ showing city name
                        locationSelect.appendChild(option);
                    });
                });
        }
    });

    locationSelect.addEventListener('change', function () {
        const universityId = uniSelect.value;
        const locationId = this.value;

        deptSelect.innerHTML = `<option value="">-- Select Department --</option>`;

        if (universityId && locationId) {
            fetch(`/admin/get-departments/${universityId}/${locationId}`)
                .then(response => response.json())
                .then(departments => {
                    departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        deptSelect.appendChild(option);
                    });
                });
        }
    });

    deptSelect.addEventListener('change', function () {
        const departmentId = this.value;

        supervisorSelect.innerHTML = `<option value="">-- Select Supervisor --</option>`;

        if (departmentId) {
            fetch(`/admin/get-supervisors/${departmentId}`)
                .then(response => response.json())
                .then(supervisors => {
                    supervisors.forEach(sup => {
                        const option = document.createElement('option');
                        option.value = sup.id;
                        option.textContent = `${sup.name} (${sup.reg_no})`;
                        supervisorSelect.appendChild(option);
                    });
                });
        }
    });

</script>


@endsection