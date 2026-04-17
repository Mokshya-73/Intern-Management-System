@extends('layouts.app')

@section('content')
@php
    use App\Models\ISession;
    use App\Models\InternSession;
    $approver2 = \App\Models\Approver2::where('reg_no', auth()->user()->reg_no)->first();
@endphp

<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <!-- Modern Notification -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden transition-all duration-300 transform translate-x-full opacity-0">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <span id="notification-message"></span>
        </div>
    </div>


    <main class="px-4 py-6 sm:px-6 max-w-7xl mx-auto flex-grow w-full">
        <!-- Blue Title Bar -->
        <div class="space-y-4 mb-6">
            <h3 class="text-lg font-semibold text-white bg-blue-900 px-4 py-2 rounded-md text-center">Approver 2 Dashboard</h3>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow border-2  mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead class="bg-blue-300">
                        <tr>
                            <th class="px-4 py-2 border">Reg No</th>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Certificate Name</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                            <tr class="border-b">
                                <td class="px-4 py-2 border">{{ $intern->reg_no }}</td>
                                <td class="px-4 py-2 border">{{ $intern->name }}</td>
                                <td class="px-4 py-2 border">{{ $intern->certificate_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2  flex gap-2">
                                    <button class="bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800 transition-colors"
                                        onclick="loadSessions('{{ $intern->reg_no }}', '{{ $intern->certificate_name ?? $intern->name }}')">
                                        View
                                    </button>
                                    @if($intern->certificate_generated_at)
                                    <a href="{{ route('certificate.download', ['reg_no' => $intern->reg_no]) }}"
                                       class="bg-blue-900 text-white px-3 py-1 rounded hover:bg-blue-800 transition-colors" target="_blank">
                                        Download
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div id="sessionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white w-full max-w-3xl rounded-lg p-6 relative border-2 border-blue-900">
                <!-- Blue Modal Title -->
                <div class="space-y-4 mb-4">
                    <h3 class="text-lg font-semibold text-white bg-blue-900 px-4 py-2 rounded-md">Session Approval - <span id="internCertName"></span></h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border mb-4">
                        <thead>
                        <tr class="bg-blue-300 text-sm">
                            <th class="px-3 py-2 border">Session</th>
                            <th class="px-3 py-2 border">Supervisor</th>
                            <th class="px-3 py-2 border">Project</th>
                            <th class="px-3 py-2 border">Tasks Avg</th>
                            <th class="px-3 py-2 border">Approved 1</th>
                            <th class="px-3 py-2 border">Approved 2</th>
                        </tr>
                    </thead>

                        <tbody id="sessionList"></tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-2">
                    <button onclick="document.getElementById('sessionModal').classList.add('hidden')"
                            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition-colors">Close</button>
                    <button id="approveBtn" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800 transition-colors hidden"
                            onclick="approveAll()">Approve All</button>
                </div>
            </div>
        </div>
        
    </main>

    <footer class="bg-[#00204F] text-white text-center py-2 text-sm sm:text-base">
        © 2025 Sri Lanka Telecom IT - Digital Platforms, All rights reserved.
    </footer>
</div>

<script>
    let currentRegNo = '';

    function loadSessions(regNo, certName) {
    currentRegNo = regNo;
    document.getElementById('sessionModal').classList.remove('hidden');
    document.getElementById('internCertName').innerText = certName;

    fetch(`/approver2/intern/${regNo}/sessions`)
        .then(res => res.json())
        .then(data => {
            let allApproved1 = true;
            let allApproved2 = true;
            let html = '';

            data.forEach((session, i) => {
                if (!session.approver1_approved) allApproved1 = false;
                if (!session.approver2_approved) allApproved2 = false;

                // Calculate average rating %
                let avgRating = 0;
                if (session.tasks && session.tasks.length > 0) {
                    const ratedTasks = session.tasks.filter(t => t.rating !== null);
                    const total = ratedTasks.reduce((sum, t) => sum + t.rating, 0);
                    avgRating = ratedTasks.length > 0 ? (total / (ratedTasks.length * 5)) * 100 : 0;
                }

                html += `
                    <tr>
                        <td class="border px-3 py-2 text-center">${i + 1}</td>
                        <td class="border px-3 py-2 text-center">${session.supervisor?.name ?? 'N/A'}</td>
                        <td class="border px-3 py-2 text-center">${session.project_name ?? 'N/A'}</td>
                        <td class="border px-3 py-2 text-center">
                            <div class="text-sm font-semibold text-blue-800">${avgRating.toFixed(0)}%</div>
                            <div class="w-full bg-gray-200 rounded h-2 mt-1">
                                <div class="bg-green-500 h-2 rounded" style="width: ${avgRating.toFixed(0)}%"></div>
                            </div>
                        </td>
                        <td class="border px-3 py-2 text-center">
                            ${session.approver1_approved ? '<span class="text-green-600 font-bold">✅</span>' : '<span class="text-red-600 font-bold">❌</span>'}
                        </td>
                        <td class="border px-3 py-2 text-center">
                            ${session.approver2_approved ? '<span class="text-green-600 font-bold">✅</span>' : '<span class="text-red-600 font-bold">❌</span>'}
                        </td>
                    </tr>`;
            });

            document.getElementById('sessionList').innerHTML = html;
            document.getElementById('approveBtn').classList.toggle('hidden', !(allApproved1 && !allApproved2));
        });
}


    function approveAll() {
        fetch(`/approver2/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ reg_no: currentRegNo })
        })
        .then(res => res.json())
        .then(data => {
            showNotification(data.message || "Approved the intern and generated certificate successfully!");
            setTimeout(() => window.location.reload(), 2000);
        })
        .catch(error => {
            showNotification("An error occurred while approving.", 'error');
        });
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationMsg = document.getElementById('notification-message');

        // Set message
        notificationMsg.textContent = message;

        // Set color based on type
        const notificationInner = notification.querySelector('div');
        notificationInner.className = type === 'success'
            ? 'bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center'
            : 'bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center';

        // Show notification with slide-in animation
        notification.classList.remove('hidden', 'translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');

        // Hide after 3 seconds with slide-out animation
        setTimeout(() => {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full', 'opacity-0');

            // Fully hide after animation completes
            setTimeout(() => notification.classList.add('hidden'), 300);
        }, 3000);
    }
</script>
@endsection
