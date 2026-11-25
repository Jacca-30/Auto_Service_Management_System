<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prisijungimas – Autoservisas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center px-4 py-10">

<main class="w-full max-w-md">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-600 text-white text-3xl mb-4">
            🚗
        </div>
        <h1 class="text-3xl font-bold text-white">
            Autoservisas
        </h1>
        <p class="text-slate-400 mt-2">
            Prisijunkite prie valdymo sistemos
        </p>
    </div>

    <section class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-6">
            Prisijungimas
        </h2>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                    El. paštas
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="vardas@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Slaptažodis
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Įveskite slaptažodį"
                >
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                >
                <label for="remember" class="ml-2 text-sm text-slate-600">
                    Prisiminti mane
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-blue-700 transition"
            >
                Prisijungti
            </button>
        </form>
    </section>

    <p class="text-center text-sm text-slate-500 mt-6">
        Autoserviso valdymo sistema
    </p>

</main>

</body>
</html>
