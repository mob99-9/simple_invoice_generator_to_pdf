<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Invoices')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Larger tap targets + no zoom on iOS inputs */
        input, select, textarea, button { font-size: 16px; }
        body { -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-slate-900 text-white sticky top-0 z-10 shadow">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('invoices.index') }}" class="text-lg font-semibold tracking-tight">
                🧾 Invoices
            </a>
            <a href="{{ route('invoices.create') }}"
               class="bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-white text-sm font-medium px-3 py-2 rounded-lg">
                + New
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-5 pb-24">
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-emerald-100 text-emerald-800 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Please fix the following:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
