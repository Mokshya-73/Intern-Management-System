<!-- Navigation Bar -->
<nav class="bg-[#00204F] text-white px-4 py-3 shadow-md">
    <div class="flex flex-wrap justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="https://i.postimg.cc/QCkgQS5p/SLTMobitel-Logo-svg.png" alt="Logo" class="h-8" />
        </div>

        @if(Route::currentRouteName() === 'intern.dashboard')
            <!-- Sessions -->
            <div class="flex-1 flex justify-center items-center flex-wrap gap-4 text-xs md:text-sm font-medium mt-2 md:mt-0">
                @foreach($iSessions as $session)
                    <button
                        @click="activeSession = '{{ $session->id }}'; selectedSessionName = '{{ $session->session_name }} ({{ $session->session_time_period }})';"
                        :class="activeSession === '{{ $session->id }}'
                            ? 'relative after:block after:h-0.5 after:bg-[#6cbf3c] after:w-full after:mt-1 text-white font-semibold'
                            : 'hover:text-green-400 transition-all duration-200'"
                        class="px-1 md:px-2 py-1"
                    >
                        {{ $session->session_name }} ({{ $session->session_time_period }})
                    </button>
                @endforeach
            </div>
        @endif


        <!-- Complaints & Profile -->
        <div class="flex items-center gap-4 text-xs md:text-sm">
            <!-- Dashboard Link -->
            <a href="{{ route('intern.dashboard') }}" class="hover:text-green-400 transition">Dashboard</a>

            <!-- Complaints Link -->
            <a href="{{ route('intern.complaints.index') }}" class="hover:text-green-400 transition">Complaints</a>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-green-400 transition">
                    <i class="fa-solid fa-circle-user text-lg text-[#6cbf3c]"></i>
                    <span class="text-[#6cbf3c]">{{ $intern->name }}</span>
                    <i class="fa-solid fa-caret-down text-[#6cbf3c]"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-2 w-40 bg-white text-gray-700 rounded shadow-lg text-sm z-50">
                    <a href="{{ route('intern.profile.show') }}"
                        class="block px-4 py-2 hover:bg-blue-100">Profile <i class="fa-solid fa-gear float-right"></i></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-blue-100">
                            Logout <i class="fa-solid fa-right-from-bracket float-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
