<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <title>Autószerviz Napló</title>

</head>
<body class="bg-gray-100 text-gray-900">

    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold">Autószerviz</a>
            <div>
                <a href="{{ route('home') }}" class="px-4 hover:underline">Főoldal</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6 min-h-screen">
        @yield('content')
    </div>

    <footer class="bg-blue-600 text-white text-center p-4 mt-6">
        <p>&copy; {{ date('Y') }} Autószerviz Napló</p>
    </footer>

</body>
</html>
