
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autoserviso sistema')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">

<header class="bg-slate-900 text-white shadow-lg">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">

        <a href="{{ route('dashboard') }}"
           class="text-xl font-bold">
            🔧 AutoServisas
        </a>

        @auth
            <nav class="flex flex-wrap items-center gap-2">

                <a href="{{ route('dashboard') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-slate-700">
                    🏠 Pagrindinis
                </a>

                <a href="{{ route('customers.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-slate-700">
                    👥 Klientai
                </a>

                <a href="{{ route('repair-orders.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-slate-700">
                    🔧 Remonto užsakymai
                </a>

                <a href="{{ route('inventory.index') }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition hover:bg-slate-700">
                    📦 Inventorius
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold transition hover:bg-red-700">
                        Atsijungti
                    </button>
                </form>

            </nav>
        @endauth

    </div>
</header>

<main class="mx-auto max-w-7xl px-6 py-8">
    @if (session('success'))
        <div class="mb-5 rounded-lg bg-green-100 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>
