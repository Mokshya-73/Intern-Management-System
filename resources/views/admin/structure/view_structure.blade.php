@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50">
            <h2 class="text-3xl font-bold text-gray-800 text-center">
                
                Assigned University Structures
            </h2>
        </div>

        <div class="p-6" x-data="{ expanded: {} }">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($universities as $university)
                <div class="border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-blue-200 h-full flex flex-col">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                            </svg>
                            {{ $university->name }}
                            <span class="ml-2 text-sm font-normal bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $university->type }}</span>
                        </h3>
                        <div class="flex items-center mt-2 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $university->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center mt-1 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Established: {{ $university->established_year }}</span>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 flex-grow">
                        <button @click="expanded[{{ $university->id }}] = !expanded[{{ $university->id }}]" 
                                class="flex items-center text-blue-600 hover:text-blue-800 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 transition-transform duration-200"
                                 :class="{ 'rotate-90': expanded[{{ $university->id }}] }"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <span>View Locations ({{ $university->locations->count() }})</span>
                        </button>

                        <div class="tree-children ml-4 pl-3 border-l-2 border-blue-200"
                             x-show="expanded[{{ $university->id }}]" x-collapse>
                            @foreach($university->locations as $location)
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-2">
                                        <h4 class="font-medium text-gray-700">{{ $location->city }}</h4>
                                        <p class="text-xs text-gray-500">{{ $location->address }}</p>
                                        
                                        @foreach($location->departments as $dept)
                                        <div class="mt-3 ml-2 pl-2 border-l-2 border-gray-200">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span class="font-medium">{{ $dept->name }}</span>
                                            </div>

                                            @php
                                                $deptHod = \App\Models\DepartmentHod::where('department_id', $dept->id)->where('is_active', 1)->first();
                                            @endphp

                                            @if($deptHod && $deptHod->hod)
                                            <div class="ml-4 mt-1 text-sm">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span class="font-medium">HOD:</span>
                                                    <span class="ml-1">{{ $deptHod->hod->name }} ({{ $deptHod->hod->reg_no }})</span>
                                                </div>
                                                
                                                <form action="{{ route('admin.structure.change.hod') }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="department_id" value="{{ $dept->id }}">
                                                    
                                                    <label for="new_hod_id" class="text-sm text-gray-600">Change to:</label>
                                                    <select name="new_hod_id" required class="border border-gray-300 rounded p-1 text-sm mb-3">
                                                        <option value="">-- Select New HOD --</option>
                                                        @foreach(\App\Models\Hod::all() as $hod)
                                                            @if($deptHod->hod_id !== $hod->id)
                                                                <option value="{{ $hod->id }}">{{ $hod->name }} ({{ $hod->reg_no }})</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                    <button type="submit" class="ml-2 px-3 py-1 text-white bg-green-600 hover:bg-green-700 rounded text-xs">
                                                        Change HOD
                                                    </button>
                                                </form>


                                                @php
                                                    $supervisors = \App\Models\HODSupervisor::where('hod_id', $deptHod->hod_id)->where('is_active', 1)->get();
                                                @endphp

                                                @if($supervisors->count())
                                                <div class="mt-2 ml-4">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                        <span class="font-medium">Supervisors:</span>
                                                    </div>
                                                    <ul class="mt-1 space-y-1">
                                                        @foreach($supervisors as $sup)
                                                        <li class="flex items-center justify-between text-xs">
                                                            <span>{{ optional($sup->supervisor)->name }} ({{ optional($sup->supervisor)->reg_no }})</span>
                                                            <form action="{{ route('admin.structure.remove.supervisor', $sup->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="reason" value="Reassigned due to performance">
                                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    Remove
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            @else
                                            <div class="ml-4 mt-1 text-xs text-red-500 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                No HOD assigned
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
       <a href="{{ route('admin.dashboard') }}" class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
</div>

<style>
    .tree-children {
        overflow: hidden;
        transition: all 0.3s ease;
    }
    [x-cloak] { display: none !important; }
</style>
@endsection