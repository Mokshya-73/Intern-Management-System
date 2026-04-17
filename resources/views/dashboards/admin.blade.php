@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen p-4 sm:p-6 md:p-8">
    <div class="container mx-auto">
        <h2 class="text-xl sm:text-2xl font-bold mb-2 text-gray-800  sm:text-left">Welcome Admin,</h2>
        <h2 class="text-xl sm:text-2xl mb-6 text-gray-800 text-center">System Admin Dashboard</h2>

        <div class="max-w-7xl mx-auto bg-white border-2 border-blue-400 rounded-lg p-4 sm:p-6 shadow-md mt-6 sm:mt-10">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- Left Column -->
                <div class="flex flex-col gap-6 w-full lg:w-2/3">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 overflow-auto p-2 border border-blue-200 rounded-lg">
                        @php
                            $cards = [
                                ['title' => 'Ongoing Interns', 'count' => \App\Models\InternProfile::where('is_active', 1)->count(), 'color' => 'text-blue-500'],
                                ['title' => 'Supervisors', 'count' => \App\Models\Supervisor::count(), 'color' => 'text-green-500'],
                                ['title' => 'Session Assignments', 'count' => \App\Models\InternSession::count(), 'color' => 'text-yellow-500'],
                                ['title' => 'Heads of Department', 'count' => \App\Models\Hod::count(), 'color' => 'text-indigo-600'],
                                ['title' => 'Approver 1', 'count' => \App\Models\Approver1::count(), 'color' => 'text-red-500'],
                                ['title' => 'Approver 2', 'count' => \App\Models\Approver2::count(), 'color' => 'text-purple-500'],
                                ['title' => 'Training Schools ', 'count' => \App\Models\University::count(), 'color' => 'text-pink-600'],
                                 ['title' => 'University ', 'count' => \App\Models\Unis::count(), 'color' => 'text-green-700'],
                            ];
                        @endphp

                        @foreach ($cards as $card)
        
                            <div class="bg-white border border-blue-400 rounded-2xl p-4  flex flex-col justify-between shadow-md hover:border-blue-600 hover:shadow-[0_10px_25px_rgba(59,130,246,0.3)] hover:scale-[1.03] transition-transform duration-300 ease-in-out cursor-pointer">
                                <h3 class="text-center font-semibold text-sm sm:text-base text-gray-900">{{ $card['title'] }}</h3>
                                <p class="text-center text-3xl sm:text-4xl font-bold {{ $card['color'] }}">{{ $card['count'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Promote Intern to Supervisor -->
                    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Promote Intern to Supervisor</h3>
                        <form method="GET" action="{{ route('admin.dashboard.promote') }}" class="flex flex-col sm:flex-row gap-2 mb-3">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Reg No or Name" class="w-full px-3 py-2 border rounded shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                            <button class="bg-blue-900 text-white hover:bg-blue-700 px-4 py-2 rounded text-sm w-full sm:w-auto">Search</button>
                        </form>

                        @if(isset($interns) && count($interns) > 0)
                            <div class="overflow-auto max-h-[300px] text-sm">
                                <table class="w-full table-auto border text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-2 py-1">Reg No</th>
                                            <th class="px-2 py-1">Name</th>
                                            <th class="px-2 py-1">Email</th>
                                            <th class="px-2 py-1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($interns as $intern)
                                            <tr>
                                                <td class="border px-2 py-1">{{ $intern->reg_no }}</td>
                                                <td class="border px-2 py-1">{{ $intern->name }}</td>
                                                <td class="border px-2 py-1">{{ $intern->email }}</td>
                                                <td class="border px-2 py-1">
                                                    <form method="POST" action="{{ route('admin.dashboard.promote.action', $intern->reg_no) }}" onsubmit="return confirm('Promote {{ $intern->name }}?');">
                                                        @csrf
                                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                                            Promote
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(request('search'))
                            <p class="text-gray-600 mt-2 text-sm">No interns found for "{{ request('search') }}"</p>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="flex flex-col gap-6 w-full lg:w-1/3">
                    <!-- Chart Section -->
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 text-center mb-4">Internship Overview</h3>
                        <div class="w-full" style="height: 300px;">
                            <canvas id="dashboardChart"></canvas>
                        </div>
                    </div>

                    <!-- Manage Credentials -->
                    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Manage User Credentials</h3>
                        <form action="{{ route('admin.users.edit.credentials', ['reg_no' => '__DUMMY__']) }}" method="GET" onsubmit="this.action = this.action.replace('__DUMMY__', this.reg_no.value);">
                            <label for="reg_no" class="block text-sm font-medium text-gray-700 mb-1">Enter Reg No</label>
                            <input type="text" name="reg_no" id="reg_no" placeholder="e.g., 20032" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2" required pattern="\d{5}">
                            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm w-full sm:w-auto">
                                Edit Email or Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Ongoing Interns',
                    'Supervisors',
                    'Session Assignments',
                    'Heads of Department',
                    'Approver 1',
                    'Approver 2'
                ],
                datasets: [{
                    label: 'Total Count',
                    data: [
                        {{ \App\Models\InternProfile::where('is_active', 1)->count() }},
                        {{ \App\Models\Supervisor::count() }},
                        {{ \App\Models\InternSession::count() }},
                        {{ \App\Models\Hod::count() }},
                        {{ \App\Models\Approver1::count() }},
                        {{ \App\Models\Approver2::count() }}
                    ],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#6366f1',
                        '#ef4444',
                        '#8b5cf6'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</div>
@endsection
