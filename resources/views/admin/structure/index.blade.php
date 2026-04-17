@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50">
            <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-blue-600"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Training Schools Structure
            </h2>
        </div>

        <div class="p-6" x-data="{ expanded: [] }">
            <!-- Public Universities Section -->
            <div class="mb-12">
                <div class="flex items-center mb-6">
                   
                    <h3 class="text-2xl font-bold text-gray-800">Public Training Schools</h3>
                </div>

                @if($universities->where('type', 'Public')->isEmpty())
                    <div class="bg-blue-50 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-3 text-gray-600">No public universities found</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($universities->where('type', 'Public') as $university)
                            <div class="border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-blue-200 h-full flex flex-col">
                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $university->name }}
                                    </h3>
                                    <div class="flex items-center mt-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Est: {{ $university->established_year }}</span>
                                    </div>
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate">{{ $university->email ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="px-6 py-4 border-t border-gray-100 flex-grow">
                                    <button @click="expanded.includes({{ $university->id }}) ? expanded = expanded.filter(id => id !== {{ $university->id }}) : expanded.push({{ $university->id }})"
                                            class="flex items-center text-blue-600 hover:text-blue-800 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 transition-transform duration-200"
                                             :class="{ 'rotate-90': expanded.includes({{ $university->id }}) }"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span>View Locations ({{ $university->locations->count() }})</span>
                                    </button>

                                    <div class="tree-children ml-4 pl-3 border-l-2 border-blue-200"
                                         x-show="expanded.includes({{ $university->id }})" x-collapse>
                                        @foreach($university->locations as $location)
                                            <div class="mb-3">
                                                <div class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <div class="ml-2">
                                                        <h5 class="font-medium">{{ $location->city }}</h5>
                                                        <p class="text-xs text-gray-500">{{ $location->address }}</p>
                                                        
                                                        @if(count($location->departments))
                                                            <div class="mt-2 ml-2 pl-2 border-l-2 border-gray-200 space-y-2">
                                                                @foreach($location->departments as $department)
                                                                    <div>
                                                                        <div class="flex items-center text-sm">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                                            </svg>
                                                                            <span>{{ $department->name }}</span>
                                                                        </div>
                                                                        
                                                                        @php
                                                                            $hod = \App\Models\DepartmentHod::where('department_id', $department->id)
                                                                                ->where('is_active', 1)
                                                                                ->first();
                                                                        @endphp
                                                                        
                                                                        @if($hod && $hod->hod)
                                                                            <div class="ml-4 mt-1 text-xs">
                                                                                <span class="font-medium">HOD:</span> {{ $hod->hod->name }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Private Universities Section -->
            <div>
                <div class="flex items-center mb-6">
                  
                    <h3 class="text-2xl font-bold text-gray-800">Private Training Schools</h3>
                </div>

                @if($universities->where('type', 'Private')->isEmpty())
                    <div class="bg-purple-50 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-3 text-gray-600">No private Training Schools found</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($universities->where('type', 'Private') as $university)
                            <div class="border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-purple-200 h-full flex flex-col">
                                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $university->name }}
                                    </h3>
                                    <div class="flex items-center mt-2 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Est: {{ $university->established_year }}</span>
                                    </div>
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate">{{ $university->email ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="px-6 py-4 border-t border-gray-100 flex-grow">
                                    <button @click="expanded.includes({{ $university->id }}) ? expanded = expanded.filter(id => id !== {{ $university->id }}) : expanded.push({{ $university->id }})"
                                            class="flex items-center text-purple-600 hover:text-purple-800 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 transition-transform duration-200"
                                             :class="{ 'rotate-90': expanded.includes({{ $university->id }}) }"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span>View Locations ({{ $university->locations->count() }})</span>
                                    </button>

                                    <div class="tree-children ml-4 pl-3 border-l-2 border-purple-200"
                                         x-show="expanded.includes({{ $university->id }})" x-collapse>
                                        @foreach($university->locations as $location)
                                            <div class="mb-3">
                                                <div class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-1 text-purple-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <div class="ml-2">
                                                        <h5 class="font-medium">{{ $location->city }}</h5>
                                                        <p class="text-xs text-gray-500">{{ $location->address }}</p>
                                                        
                                                        @if(count($location->departments))
                                                            <div class="mt-2 ml-2 pl-2 border-l-2 border-gray-200 space-y-2">
                                                                @foreach($location->departments as $department)
                                                                    <div>
                                                                        <div class="flex items-center text-sm">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                                            </svg>
                                                                            <span>{{ $department->name }}</span>
                                                                        </div>
                                                                        
                                                                        @php
                                                                            $hod = \App\Models\DepartmentHod::where('department_id', $department->id)
                                                                                ->where('is_active', 1)
                                                                                ->first();
                                                                        @endphp
                                                                        
                                                                        @if($hod && $hod->hod)
                                                                            <div class="ml-4 mt-1 text-xs">
                                                                                <span class="font-medium">HOD:</span> {{ $hod->hod->name }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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