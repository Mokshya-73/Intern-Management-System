@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-5xl w-full bg-white rounded-lg shadow-md p-6 border-2 border-blue-800">
        <h3 class="text-lg font-semibold text-white bg-blue-900 px-4 py-2 my-3 rounded-md text-center">Review & Update Tasks - {{ $session->project_name }}</h3>

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

        <form method="POST" action="{{ route('supervisor.tasks.update', $session->id) }}" id="tasksForm">
            @csrf
            @method('POST')

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-collapse border-gray-300">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">#</th>
                            <th class="border px-4 py-2 text-left">Task Name</th>
                            <th class="border px-4 py-2 text-left">Rating</th>
                            <th class="border px-4 py-2 text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($session->tasks as $index => $task)
                        <tr class="border-t">
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>

                            <td class="border px-4 py-2">
                                @if($task->id)
                                    <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task->id }}">
                                @endif
                                <input type="text" name="tasks[{{ $index }}][task_name]" value="{{ $task->task_name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </td>

                            <td class="border px-4 py-2">
                                <div class="rating-bubbles flex justify-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center rating-bubble"
                                                data-rating="{{ $i }}"
                                                data-task-index="{{ $index }}"
                                                onclick="setRating(this)">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                    <input type="hidden"
                                           name="tasks[{{ $index }}][rating]"
                                           id="rating-{{ $index }}"
                                           value="{{ $task->rating ?? '' }}"
                                           required>
                                </div>
                            </td>

                            <td class="border px-4 py-2">
                                <textarea name="tasks[{{ $index }}][description]"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          rows="2"
                                          required>{{ $task->description }}</textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-left">
                                <a href="{{ route('supervisor.dashboard') }}"
                                   class="flex items-center text-blue-600 hover:text-blue-800 px-4 py-2 rounded-md transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Back
                                </a>
                            </td>
                            <td colspan="2" class="px-4 py-3 text-right">
                                <button type="submit" class="px-6 py-2 bg-blue-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    Save Tasks
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
</div>

<style>
    .rating-bubble {
        transition: all 0.2s ease;
    }
    .rating-bubble.selected {
        background-color: #1a56db;
        color: white;
        border-color: #1a56db;
    }
    .rating-bubble:hover {
        background-color: #e0e7ff;
        cursor: pointer;
    }
</style>

<script>
    // Initialize ratings on page load
    document.addEventListener('DOMContentLoaded', function() {
        @foreach ($session->tasks as $index => $task)
            @if($task->rating)
                const rating{{ $index }} = document.querySelector(`button[data-task-index="{{ $index }}"][data-rating="{{ $task->rating }}"]`);
                if(rating{{ $index }}) {
                    rating{{ $index }}.classList.add('selected');
                }
            @endif
        @endforeach
    });

    // Set rating function
    function setRating(element) {
        const rating = element.getAttribute('data-rating');
        const taskIndex = element.getAttribute('data-task-index');

        // Remove selected class from all bubbles in this row
        const bubbles = document.querySelectorAll(`button[data-task-index="${taskIndex}"]`);
        bubbles.forEach(bubble => {
            bubble.classList.remove('selected');
        });

        // Add selected class to clicked bubble
        element.classList.add('selected');

        // Update hidden input value
        document.getElementById(`rating-${taskIndex}`).value = rating;
    }

    // Form validation
    document.getElementById('tasksForm').addEventListener('submit', function(e) {
        let isValid = true;

        // Check all required fields
        const requiredFields = this.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields before submitting.');
        }
    });
</script>
@endsection
