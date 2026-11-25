
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąmatos peržiūra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-700">

<div class="max-w-4xl mx-auto px-4 py-10 space-y-6">

    <div class="bg-slate-800 text-white rounded-xl p-6 sm:p-8 shadow-sm">
        <h1 class="text-2xl sm:text-3xl font-bold">
            Automobilio remonto sąmata
        </h1>
        <p class="text-slate-300 mt-2">
            Jūsų autoserviso parengtas remonto pasiūlymas
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <p class="text-lg">
            Sveiki, <strong>{{ $estimate->repairOrder->customerVehicle->customer->name }}</strong>!
        </p>

        <p class="text-sm text-slate-500 mt-2">
            Peržiūrėkite autoserviso parengtą remonto sąmatą.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">
            Automobilio informacija
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <p class="text-sm text-slate-500">Markė ir modelis</p>
                <p class="font-medium text-slate-800 mt-1">
                    {{ $estimate->repairOrder->customerVehicle->vehicle->make }}
                    {{ $estimate->repairOrder->customerVehicle->vehicle->model }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Valstybinis numeris</p>
                <p class="font-medium text-slate-800 mt-1">
                    {{ $estimate->repairOrder->customerVehicle->license_plate ?? 'Nenurodyta' }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">
                Sąmatos eilutės
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-5 py-3 font-semibold">Tipas</th>
                    <th class="px-5 py-3 font-semibold">Aprašymas</th>
                    <th class="px-5 py-3 font-semibold">Kiekis</th>
                    <th class="px-5 py-3 font-semibold">Vieneto kaina</th>
                    <th class="px-5 py-3 font-semibold">Suma</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                @foreach ($estimate->items as $item)
                    <tr>
                        <td class="px-5 py-4 text-slate-600">
                            @if ($item->type === 'labor')
                                Darbas
                            @elseif ($item->type === 'part')
                                Detalė
                            @else
                                Medžiaga
                            @endif
                        </td>

                        <td class="px-5 py-4 font-medium text-slate-800">
                            {{ $item->description }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            {{ $item->quantity }}
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            {{ number_format((float) $item->unit_price, 2) }} €
                        </td>

                        <td class="px-5 py-4 font-medium whitespace-nowrap">
                            {{ number_format((float) $item->total_price, 2) }} €
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-blue-50 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <span class="text-sm font-medium text-slate-600">
                Bendra remonto sąmata
            </span>

            <span class="text-3xl font-bold text-blue-700 tabular-nums">
                {{ number_format((float) $estimate->total_amount, 2) }} €
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-800">
                Sąmatos galiojimas
            </h2>

            <p class="text-sm text-slate-500 mt-2">
                Sąmata galioja iki:
            </p>

            <p class="font-semibold text-slate-800 mt-1">
                {{ $estimate->expires_at->format('Y-m-d H:i') }}
            </p>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h2 class="text-lg font-semibold text-slate-800">
                Jūsų sprendimas
            </h2>

            <p class="text-sm text-slate-500 mt-2 mb-5">
                Ar sutinkate su pateikta remonto sąmata?
            </p>

            <div class="flex flex-col sm:flex-row gap-3">

                <form action="{{ route('estimates.approve', $token) }}"
                      method="POST"
                      class="flex-1"
                      onsubmit="return confirm('Ar tikrai norite patvirtinti sąmatą?')">
                    @csrf

                    <button type="submit"
                            class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-3 transition">
                        ✓ Patvirtinti sąmatą
                    </button>
                </form>

                <form action="{{ route('estimates.reject', $token) }}"
                      method="POST"
                      class="flex-1"
                      onsubmit="return confirm('Ar tikrai norite atmesti sąmatą?')">
                    @csrf

                    <button type="submit"
                            class="w-full rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3 transition">
                        ✕ Atmesti sąmatą
                    </button>
                </form>

            </div>
        </div>
    </div>

    <div class="text-center text-xs text-slate-400 py-3">
        Šis puslapis skirtas Jūsų automobilio remonto sąmatai peržiūrėti.
    </div>

</div>

</body>
</html>
