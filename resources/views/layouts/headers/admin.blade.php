<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800"
     x-data="{
        mobileMenuOpen: false,
        statusFilter: window.location.hash === '#completed' ? 'completed' : 'ongoing',
        searchRegNo: '',
        showComplainModal: false,
        activeTab: window.location.hash === '#completed' ? 'completed' : 'ongoing'
     }"><!-- Navigation Bar -->
<nav class="bg-[#00204F] text-white px-4 py-3">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="w-full md:w-auto flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="https://i.postimg.cc/2874YvsJ/logo.png" alt="Logo" class="h-10" />
             
            </a>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>

        <div :class="{'hidden md:block': !mobileMenuOpen, 'block': mobileMenuOpen}" class="w-full md:w-auto">
         <ul class="flex flex-col md:flex-row items-center gap-4 text-sm md:text-base py-4 md:py-0">
    <!-- University -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'universities' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-university mr-1"></i> University
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.unis.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-university mr-2"></i> Manage Universities and Locations
            </a>
        </div>
    </li>
            
    <!-- Interns -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'interns' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-users mr-1"></i> Interns
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.interns.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Add Intern
            </a>
            <a href="{{ route('admin.interns.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-users-cog mr-2"></i> Manage Intern
            </a>
        </div>
    </li>

    <!-- Supervisors -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'supervisors' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-user-tie mr-1"></i> Supervisors
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.supervisors.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Add Supervisor
            </a>
            <a href="{{ route('admin.structure.assignSupervisor') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-plus-circle mr-2"></i> Assign Supervisor
            </a>
            <a href="{{ route('admin.supervisors.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-users-cog mr-2"></i> Manage Supervisor
            </a>
        </div>
    </li>

    <!-- Sessions -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'sessions' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-calendar-alt mr-1"></i> Sessions
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.sessions.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-plus-circle mr-2"></i> Add Session
            </a>
            <a href="{{ route('admin.sessions.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-tasks mr-2"></i> Manage Session
            </a>
        </div>
    </li>

    <!-- HOD -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'hods' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-user-graduate mr-1"></i> HOD
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.hods.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Add HOD
            </a>
            <a href="{{ route('admin.structure.assignHOD') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-plus-circle mr-2"></i> Assign HOD
            </a>
            <a href="{{ route('admin.hods.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-users-cog mr-2"></i> Manage HOD
            </a>
        </div>
    </li>

    <!-- Approver 1 -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'approver1' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-user-check mr-1"></i> Approver 1
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.approver1.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Add Approver 1
            </a>
            <a href="{{ route('admin.approver1.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-users-cog mr-2"></i> Manage Approver 1
            </a>
        </div>
    </li>

    <!-- Approver 2 -->
    <li class="relative" x-data="{ open: false }">
        <a href="#"
           @click.prevent="open = !open"
           :class="activeTab === 'approver2' 
                      ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
                      : 'border-b-2 border-transparent hover:border-[#6cbf3c]'"
           class="flex items-center">
            <i class="fas fa-user-check mr-1"></i> Approver 2
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>
        <div x-show="open" @click.outside="open = false" 
             class="absolute left-0 mt-1 w-44 rounded shadow-lg bg-[#00204F] text-white">
            <a href="{{ route('admin.approver2.create') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Add Approver 2
            </a>
            <a href="{{ route('admin.approver2.index') }}" class="block px-4 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-users-cog mr-2"></i> Manage Approver 2
            </a>
        </div>
    </li>

    <!-- Training Schools -->
    <li class="relative" x-data="{ open: false, showLocationDropdown: false }">
        <a href="#" 
        @click.prevent="open = !open"
        :class="open 
            ? 'border-b-2 border-[#6cbf3c] font-medium text-[#6cbf3c]' 
            : 'border-b-2 border-transparent hover:border-[#6cbf3c]'" 
        class="flex items-center px-3 py-2">
            <i class="fas fa-university mr-1"></i> Training Schools
            <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
        </a>

        <div x-show="open" @click.outside="open = false" x-transition 
            class="absolute left-0 mt-1 w-72 rounded shadow-lg bg-[#00204F] text-white z-50 px-4 py-3">

            <!-- Vertical Step Connector -->
            <div class="relative pl-6 space-y-4">
                <!-- Vertical Line -->
                <div class="absolute left-3 top-2 bottom-2 w-px bg-green-400"></div>

                <!-- Step 01 -->
                <a href="{{ route('admin.universities.create') }}" 
                class="flex items-center gap-2 relative z-10 hover:text-[#6cbf3c]">
                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full absolute left-2 top-1.5"></div>
                    <i class="fa-solid fa-university"></i>
                    <span>Add Training Schools (Step 01)</span>
                </a>

                <!-- Step 02 (with dropdown) -->
                <div class="relative z-10">
                    <button @click="showLocationDropdown = !showLocationDropdown"
                            class="flex items-center gap-2 w-full text-left hover:text-[#6cbf3c]">
                        <div class="w-2.5 h-2.5 bg-green-400 rounded-full absolute left-2 top-2"></div>
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Quick Add Location (Step 02)</span>
                    </button>

                    <!-- Sub-dropdown for location -->
                    <div x-show="showLocationDropdown" x-transition 
                        class="ml-6 mt-2 bg-white p-2 rounded text-black border border-gray-300">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Select Training School</label>
                        <select onchange="if(this.value) window.location.href=this.value;" 
                                class="w-full border rounded px-2 py-1 text-sm">
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\University::orderBy('name')->get() as $univ)
                                <option value="{{ route('admin.locations.create', $univ->id) }}">
                                    {{ $univ->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Step 03 -->
                <a href="{{ route('admin.structure.departments.create') }}" 
                class="flex items-center gap-2 relative z-10 hover:text-[#6cbf3c]">
                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full absolute left-2 top-1.5"></div>
                    <i class="fa-solid fa-folder"></i>
                    <span>Add Department (Step 03)</span>
                </a>
            </div>

            <!-- Divider -->
            <hr class="my-3 border-gray-400">

            <!-- Other Menu Items -->
            <a href="{{ route('admin.universities.index') }}" 
            class="block px-2 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-search mr-2"></i> View Training Schools
            </a>

            <a href="{{ route('admin.structure.view') }}" 
            class="block px-2 py-2 hover:text-[#6cbf3c] flex items-center">
                <i class="fa-solid fa-user-gear mr-2"></i> HOD & SUPERVISOR Assignation
            </a>
        </div>
    </li>


</ul>
        </div>
        
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded hover:text-green-400">
                <i class="fa-solid fa-circle-user text-2xl"></i>
                <span>Administrator </span>
                <i class="fa-solid fa-caret-down"></i>
            </button>

            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md z-50">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100">
                    <i class="fa-solid fa-gear mr-2"></i>Settings
                </a>
                <form method="HEAD" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-100">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>