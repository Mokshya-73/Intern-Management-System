@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">
        <h2 class="text-xl sm:text-2xl font-bold text-center text-gray-800 mb-6">Add New Intern</h2>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            <h3 class="font-bold mb-1">Please fix these errors:</h3>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.interns.store') }}" class="space-y-6">
            @csrf

            {{-- Account Credentials --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center">Account Credentials</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reg No</label>
                        <input type="number" name="reg_no" required pattern="\d{5}" min="10000" max="99999"
                            title="Enter exactly 5 digits"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Personal Information --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name on Certificate</label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('certificate_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                        <input type="number" name="mobile" value="{{ old('mobile') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('mobile') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC</label>
                        <input type="number" name="nic" value="{{ old('nic') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('nic') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Institution --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Institution</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">University & Location</label>
                    <select name="uni_loc_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Select University & Location --</option>
                        @foreach($locations->sortBy(fn($loc) => $loc->unis->uni_name) as $loc)
                        <option value="{{ $loc->id }}" {{ old('uni_loc_id') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->unis->uni_name }} - {{ $loc->location }}
                        </option>
                        @endforeach
                    </select>
                    @error('uni_loc_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Training Details --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Training Details</h3>
                @php
                    $start = old('training_start_date');
                    $duration = old('duration');
                    $calculatedEnd = '';
                    if ($start && $duration) {
                        $startDate = \Carbon\Carbon::parse($start);
                        switch ($duration) {
                            case '3_months': $calculatedEnd = $startDate->addMonths(3)->format('Y-m-d'); break;
                            case '6_months': $calculatedEnd = $startDate->addMonths(6)->format('Y-m-d'); break;
                            case '9_months': $calculatedEnd = $startDate->addMonths(9)->format('Y-m-d'); break;
                            case '1_year': $calculatedEnd = $startDate->addYear()->format('Y-m-d'); break;
                        }
                    }
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="training_start_date" value="{{ old('training_start_date') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('training_start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Duration</label>
                        <select name="duration" onchange="this.form.submit()" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">-- Select Duration --</option>
                            <option value="3_months" {{ old('duration') === '3_months' ? 'selected' : '' }}>3 Months</option>
                            <option value="6_months" {{ old('duration') === '6_months' ? 'selected' : '' }}>6 Months</option>
                            <option value="9_months" {{ old('duration') === '9_months' ? 'selected' : '' }}>9 Months</option>
                            <option value="1_year" {{ old('duration') === '1_year' ? 'selected' : '' }}>1 Year</option>
                        </select>
                        @error('duration') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="training_end_date" value="{{ $calculatedEnd }}" readonly
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @error('training_end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Add Intern
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
