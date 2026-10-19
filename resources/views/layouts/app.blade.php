<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Escalator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('tickets.index') }}" class="text-xl font-bold text-indigo-600 tracking-tight">
                🎫 Ticket Escalator
            </a>
            <div class="flex gap-4 text-sm font-medium">
                <a href="{{ route('tickets.index') }}"
                   class="text-gray-600 hover:text-indigo-600 transition-colors">
                    All Tickets
                </a>
                <a href="{{ route('tickets.create') }}"
                   class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 transition-colors">
                    + New Ticket
                </a>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-5xl mx-auto px-4 mt-4">
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 rounded px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 rounded px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
