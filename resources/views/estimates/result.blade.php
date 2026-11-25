
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąmatos atsakymas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

<main class="w-full max-w-lg">
    <div class="bg-white rounded-2xl shadow-lg p-8 text-center">

        <div class="mb-6">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full
                    @if ($status === 'approved') bg-green-100 text-green-600
                    @elseif ($status === 'rejected') bg-red-100 text-red-600
                    @else bg-amber-100 text-amber-600
                    @endif">
                @if ($status === 'approved')
                    <span class="text-3xl">✓</span>
                @elseif ($status === 'rejected')
                    <span class="text-3xl">✕</span>
                @else
                    <span class="text-3xl">!</span>
                @endif
            </div>
        </div>

        <h1 class="text-2xl font-bold text-slate-800 mb-6">
            Sąmatos atsakymas
        </h1>

        @if ($status === 'approved')
            <h2 class="text-xl font-semibold text-green-700 mb-3">
                Sąmata patvirtinta!
            </h2>

            <p class="text-slate-600 mb-3">
                Ačiū. Jūsų sąmata sėkmingai patvirtinta.
            </p>

            <p class="text-slate-600">
                Autoservisas gali pradėti remonto darbus.
            </p>

        @elseif ($status === 'rejected')
            <h2 class="text-xl font-semibold text-red-700 mb-3">
                Sąmata atmesta
            </h2>

            <p class="text-slate-600">
                Informavome autoservisą apie jūsų sprendimą.
            </p>

        @else
            <h2 class="text-xl font-semibold text-amber-700 mb-3">
                Nuoroda nebegalioja
            </h2>

            <p class="text-slate-600 mb-3">
                Sąmatos nuoroda nebegalioja arba sąmata jau buvo apdorota.
            </p>

            <p class="text-slate-600">
                Jeigu reikia, susisiekite su autoservisu.
            </p>
        @endif

    </div>

    <p class="text-center text-sm text-slate-400 mt-6">
        Autoserviso valdymo sistema
    </p>
</main>

</body>
</html>
