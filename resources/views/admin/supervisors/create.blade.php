@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 text-center">Add New Supervisor</h2>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded whitespace-pre-line">
            <strong>Success!</strong><br>
            {!! nl2br(e(session('success'))) !!}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 border-l-4 text-red-700 rounded">
            <h3 class="font-bold mb-1">Please fix these errors:</h3>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- First Step: Select University -->
        <form action="{{ route('admin.supervisors.create') }}" method="GET" class="mb-8">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Select University</h3>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">University</label>
                    <select name="university_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="this.form.submit()" 
                            required>
                        <option value="">-- Select University --</option>
                        @foreach($universities as $university)
                            <option value="{{ $university->id }}" {{ request('university_id') == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if($selectedUniversity)
        <form action="{{ route('admin.supervisors.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="university" value="{{ $selectedUniversity->name }}">

            <div class="space-y-4">
                <h3 class="text-lg font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center"
>Supervisor Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reg No (5 digits)</label>
                        <input type="text" name="reg_no" 
                               pattern="\d{5}" maxlength="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('reg_no') border-red-500 @enderror"
                               required>
                        @error('reg_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Designation on the Work</label>
                        <input type="text" name="designation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('designation') border-red-500 @enderror" 
                               required>
                        @error('designation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Add Supervisor
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
