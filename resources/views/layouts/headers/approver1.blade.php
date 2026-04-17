<nav class="bg-[#00204F] text-white px-4 py-3">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Logo and mobile menu button -->
            <div class="w-full md:w-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('approver1.dashboard') }}">
                        <img src="https://i.postimg.cc/QCkgQS5p/SLTMobitel-Logo-svg.png" alt="Logo" class="h-10" />
                    </a>
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen"></i>
                </button>
            </div>

            <!-- User Profile Dropdown -->
            <div x-data="{ open: false }" class="relative w-full md:w-auto">
                <button @click="open = !open" class="flex items-center justify-center md:justify-start gap-2 px-3 py-2 rounded hover:text-green-400 w-full md:w-auto">
                    <i class="fa-solid fa-circle-user text-2xl"></i>
                    <span></span>
                    <i class="fa-solid fa-caret-down"></i>
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 md:w-40 bg-white shadow-lg rounded-md z-50">
                    <a href="{{ route('approver1.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-100" @click="mobileMenuOpen = false">
                        Profile<i class="fa-solid fa-gear pl-2"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-100">
                            Logout<i class="fa-solid fa-right-from-bracket pl-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
