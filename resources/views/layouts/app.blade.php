<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Internship Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.postimg.cc/MpkT4VNc/fav.png" sizes="32x32">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    



</head>
<body class="font-[Poppins] bg-white">


    @if(auth()->check())
        @php
            $roleId = auth()->user()->role_id;
        @endphp

        @switch($roleId)
            @case(2)
                @include('layouts.headers.supervisor')
                @break

            @case(3)
                @include('layouts.headers.hod')
                @break

            @case(4)
                @include('layouts.headers.approver1')
                @break

            @case(5)
                @include('layouts.headers.approver2')
                @break

            @case(6)
                @include('layouts.headers.admin')
                @break

            @default
                @include('layouts.header')
        @endswitch
    @else
        @include('layouts.header')
    @endif



    @if (session('message'))
    <div class="fixed top-5 right-5 z-50">
        <div class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between space-x-4">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white font-bold focus:outline-none">
                &times;
            </button>
        </div>
    </div>
@endif



    <main class="w-full mt-0">
        @yield('content')
    </main>


    @include('layouts.footer')

    @if (!auth()->check())
    <script>
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                    window.location.href = "{{ route('login') }}";
                }
            });
        </script>
    @endif

</body>
</html>
