{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Unauthorized</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="text-center px-6">
        <h1 class="text-6xl font-bold text-red-500">403</h1>
        <p class="text-2xl mt-4">Unauthorized Access</p>
        <p class="text-gray-400 mt-2">You do not have permission to access this page.</p>
        <a href="{{ route('login') }}" class="mt-6 inline-block px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Return to Login
        </a>
    </div>
</body>
</html>
