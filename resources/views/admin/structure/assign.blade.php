@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Assign HOD & Supervisors</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.structure.assign') }}">
        @csrf

        <!-- University -->
        <div class="mb-4">
            <label class="block font-medium">University</label>
            <select name="university_id" class="w-full border rounded p-2" onchange="this.form.submit()">
                <option value="">-- Select University --</option>
                @foreach($universities as $uni)
                    <option value="{{ $uni->id }}" {{ old('university_id', $selectedUniversity) == $uni->id ? 'selected' : '' }}>
                        {{ $uni->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Location -->
        @if($locations->count())
        <div class="mb-4">
            <label class="block font-medium">Location</label>
            <select name="location_id" class="w-full border rounded p-2" onchange="this.form.submit()">
                <option value="">-- Select Location --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ old('location_id', $selectedLocation) == $loc->id ? 'selected' : '' }}>
                        {{ $loc->city }} - {{ $loc->address }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Department -->
        @if($departments->count())
        <div class="mb-4">
            <label class="block font-medium">Department</label>
            <select name="department_id" class="w-full border rounded p-2" onchange="this.form.submit()" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $selectedDepartment) == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- HOD + Supervisors (only after department selected) -->
        @if($selectedDepartment)
        <!-- HOD -->
        <div class="mb-4">
            <label class="block font-medium">Select HOD</label>
            <select name="hod_id" class="w-full border rounded p-2" required>
                <option value="">-- Select HOD --</option>
                @foreach($hods as $hod)
                    <option value="{{ $hod->id }}" {{ old('hod_id') == $hod->id ? 'selected' : '' }}>
                        {{ $hod->name }} ({{ $hod->reg_no }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Supervisors -->
        <div class="mb-6">
            <label class="block font-medium">Assign Supervisors</label>
            <select name="supervisor_ids[]" multiple required class="w-full border rounded p-2 h-40">
                @foreach($supervisors as $sup)
                    <option value="{{ $sup->id }}" {{ collect(old('supervisor_ids'))->contains($sup->id) ? 'selected' : '' }}>
                        {{ $sup->name }} ({{ $sup->reg_no }})
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</p>
        </div>

        <button type="submit" formaction="{{ route('admin.structure.store') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Assign Structure
        </button>
        @endif

    </form>
</div>
@endsection
