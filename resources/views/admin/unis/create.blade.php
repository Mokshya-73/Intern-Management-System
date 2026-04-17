@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 sm:p-6 bg-white border-2 border-blue-400 rounded-lg mt-6 sm:mt-10 shadow-md">

    <h2  class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5">University Management</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded shadow-sm text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded shadow-sm text-sm sm:text-base">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">

        <!-- Create University -->
        <div class="border border-blue-300 rounded-lg p-4 sm:p-5">
            <h3 class="text-base sm:text-lg font-semibold text-blue-900 mb-3 sm:mb-4">Add New University</h3>

            <form method="POST" action="{{ route('admin.unis.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">University Name</label>
                    <input type="text" name="uni_name" required placeholder="e.g., APIIT"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-700 transition text-sm">
                    Create University
                </button>
            </form>
        </div>

        <!-- Add Location -->
        <div class="border border-blue-300 rounded-lg p-4 sm:p-5">
            <h3 class="text-base sm:text-lg font-semibold text-blue-900 mb-3 sm:mb-4">Add Location to University</h3>

            <form method="POST" action="{{ route('admin.locs.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Select University</label>
                    <select name="uni_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">-- Select --</option>
                        @foreach($universities as $uni)
                            <option value="{{ $uni->id }}">{{ $uni->uni_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Location Name</label>
                    <input type="text" name="location" required placeholder="e.g., Colombo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-700 transition text-sm">
                    + Add Location
                </button>
            </form>
        </div>
    </div>

    <!-- Divider -->
  <hr class="my-8 sm:my-10 border-t-2 border-blue-300">

<!-- University Structure -->
<section class="mt-8 sm:mt-12">
    <h3 class="text-xl font-semibold bg-blue-50  border-2 border-blue-200 text-blue-900 px-4 py-1 rounded text-center mb-5"
>
        University Structure
    </h3>

    <div class="pl-3 sm:pl-6 space-y-8">
        @forelse($universities as $uni)
            <div x-data="{ open: true }" class="bg-white shadow-md rounded-lg p-4 sm:p-6 border border-blue-100">
                
                <!-- University Row -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-4 h-4 bg-blue-700 rounded-full mt-1 sm:mt-0"></div>
                    <div class="w-full">
                        <div class="flex justify-between items-center flex-wrap sm:flex-nowrap gap-4">
                            <h4 class="text-lg font-semibold text-blue-800">
                                {{ $uni->uni_name }}
                            </h4>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.unis.edit', $uni->id) }}"
                                   class="text-sm bg-green-700 hover:bg-green-600 text-white px-4 py-1.5 rounded-md shadow transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.unis.destroy', $uni->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this university?')"
                                            class="text-sm bg-red-900 hover:bg-red-700 text-white px-4 py-1.5 rounded-md shadow transition">
                                        Delete
                                    </button>
                                </form>
                                <button @click="open = !open"
                                        class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-1.5 rounded-md shadow transition">
                                    Toggle
                                </button>
                            </div>
                        </div>

                        <!-- Locations List -->
                        <div x-show="open" x-transition class="mt-4 space-y-2 text-sm text-gray-700">
                            @if($uni->loc->isNotEmpty())
                                <ul class="space-y-2">
                                    @foreach($uni->loc as $loc)
                                        <li class="flex justify-between items-center border-t pt-2">
                                            <span class="pl-1">{{ $loc->location }}</span>
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.locs.edit', $loc->id) }}"
                                                   class="text-sm bg-green-700 hover:bg-green-600 text-white px-4 py-1.5 rounded-md shadow transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.locs.destroy', $loc->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            onclick="return confirm('Delete this location?')"
                                                            class="text-sm bg-red-900 hover:bg-red-700 text-white px-4 py-1.5 rounded-md shadow transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 pl-1">No locations added.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-red-600 text-center text-sm sm:text-base">No universities found.</p>
        @endforelse
    </div>
</section>

    </div>
</section>

           
        </div>
    </div>
</div>

@endsection
