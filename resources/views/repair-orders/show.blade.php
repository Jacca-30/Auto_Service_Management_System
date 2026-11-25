@extends('layouts.app')

@section('title', 'Remonto užsakymas #' . $repairOrder->id)

@section('content')

    @php
        $statusLabels = [
            'pending' => 'Laukia priėmimo',
            'accepted' => 'Priimtas',
            'declined' => 'Atmestas',
            'diagnosing' => 'Diagnostika',
            'awaiting_approval' => 'Laukia patvirtinimo',
            'in_repair' => 'Remontuojamas',
            'completed' => 'Užbaigtas',
        ];

        $statusClasses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-blue-100 text-blue-800',
            'declined' => 'bg-red-100 text-red-800',
            'diagnosing' => 'bg-purple-100 text-purple-800',
            'awaiting_approval' => 'bg-orange-100 text-orange-800',
            'in_repair' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
        ];
    @endphp

    <div class="mb-8">
        <a href="{{ route('repair-orders.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-800">
            ← Grįžti į užsakymų sąrašą
        </a>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Remonto užsakymas #{{ $repairOrder->id }}
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Užklausa gauta:
                    {{ $repairOrder->created_at->format('Y-m-d H:i') }}
                </p>
            </div>

            <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses[$repairOrder->status] ?? 'bg-slate-100 text-slate-700' }}">
            {{ $statusLabels[$repairOrder->status] ?? $repairOrder->status }}
        </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="mb-2 font-semibold">Patikrinkite pateiktus duomenis:</p>
            <ul class="list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Užsakymo veiksmai --}}
    @if ($repairOrder->status === 'pending')
        <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-xl font-bold text-slate-800">
                Užklausos valdymas
            </h2>

            <p class="mb-5 text-sm text-slate-500">
                Pasirinkite, ar priimti kliento remonto užklausą.
            </p>

            <div class="flex flex-wrap gap-3">
                <form action="{{ route('repair-orders.accept', $repairOrder->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="rounded-lg bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                        ✓ Priimti užklausą
                    </button>
                </form>

                <form action="{{ route('repair-orders.decline', $repairOrder->id) }}"
                      method="POST"
                      onsubmit="return confirm('Ar tikrai norite atmesti šią remonto užklausą?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                        ✕ Atmesti užklausą
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Klientas ir automobilis --}}
    <div class="mb-8 grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-xl font-bold text-slate-800">
                👤 Kliento informacija
            </h2>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-500">Vardas, pavardė</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->customer->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">El. paštas</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->customer->email ?? 'Nenurodyta' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Telefonas</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->customer->phone ?? 'Nenurodyta' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-xl font-bold text-slate-800">
                🚗 Automobilio informacija
            </h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Markė ir modelis</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->vehicle->make }}
                        {{ $repairOrder->customerVehicle->vehicle->model }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Pagaminimo metai</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->vehicle->year ?? 'Nenurodyta' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Valstybinis numeris</p>
                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->license_plate ?? 'Nenurodyta' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">VIN kodas</p>
                    <p class="mt-1 break-all font-semibold text-slate-800">
                        {{ $repairOrder->customerVehicle->vin ?? 'Nenurodyta' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Gedimo aprašymas ir pastabos --}}
    <div class="mb-8 grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-bold text-slate-800">
                🔍 Kliento nurodytas gedimas
            </h2>

            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $repairOrder->customer_complaint }}</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-bold text-slate-800">
                📝 Vidinės pastabos
            </h2>

            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $repairOrder->internal_notes ?? 'Pastabų nėra.' }}</p>
        </div>

    </div>

    {{-- Diagnostika --}}
    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-xl font-bold text-slate-800">
                🔧 Diagnostika
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Nustatyti gedimai ir rekomenduojami remonto darbai.
            </p>
        </div>

        @if ($repairOrder->diagnostics->isNotEmpty())

            <div class="space-y-5">
                @foreach ($repairOrder->diagnostics as $diagnostic)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">

                        <h3 class="mb-4 font-bold text-slate-800">
                            Diagnostika #{{ $diagnostic->id }}
                        </h3>

                        <div class="mb-4">
                            <p class="mb-2 text-sm font-semibold text-slate-700">
                                Nustatyti gedimai
                            </p>

                            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $diagnostic->findings }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="mb-2 text-sm font-semibold text-slate-700">
                                Rekomenduojami remonto darbai
                            </p>

                            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $diagnostic->recommendations ?? 'Rekomendacijų nėra.' }}</p>
                        </div>

                        <div class="flex flex-wrap justify-between gap-3 border-t border-slate-200 pt-4 text-xs text-slate-500">
                        <span>
                            Atliko:
                            {{ $diagnostic->performedBy->name ?? 'Nenurodyta' }}
                        </span>

                            <span>
                            {{ $diagnostic->created_at->format('Y-m-d H:i') }}
                        </span>
                        </div>

                    </div>
                @endforeach
            </div>

        @else

            @if (in_array($repairOrder->status, ['accepted', 'diagnosing']))

                <div class="mb-5">
                    <p class="text-sm text-slate-500">
                        Užsakymui dar neatlikta diagnostika. Užpildykite žemiau esančius laukus.
                    </p>
                </div>

                <form action="{{ route('repair-orders.diagnostics.store', $repairOrder->id) }}"
                      method="POST"
                      class="space-y-5">
                    @csrf

                    <div>
                        <label for="findings" class="mb-2 block text-sm font-semibold text-slate-700">
                            Nustatyti gedimai
                        </label>

                        <textarea id="findings"
                                  name="findings"
                                  rows="5"
                                  required
                                  class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                  placeholder="Aprašykite nustatytus gedimus...">{{ old('findings') }}</textarea>

                        @error('findings')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="recommendations" class="mb-2 block text-sm font-semibold text-slate-700">
                            Rekomenduojami remonto darbai
                        </label>

                        <textarea id="recommendations"
                                  name="recommendations"
                                  rows="5"
                                  class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                  placeholder="Nurodykite rekomenduojamus remonto darbus...">{{ old('recommendations') }}</textarea>

                        @error('recommendations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Išsaugoti diagnostiką
                    </button>
                </form>

            @else

                <p class="text-sm text-slate-500">
                    Diagnostika dar neatlikta.
                </p>

            @endif

        @endif

    </div>

    {{-- Sąmatos --}}
    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-xl font-bold text-slate-800">
                💶 Sąmatos
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Remonto darbų ir reikalingų dalių kainos.
            </p>
        </div>

        @if ($repairOrder->estimates->isNotEmpty())

            <div class="space-y-8">

                @foreach ($repairOrder->estimates->sortByDesc('version') as $estimate)

                    <div>
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">

                            <div>
                                <h3 class="text-lg font-bold text-slate-800">
                                    Sąmata Nr. {{ $estimate->version }}
                                </h3>

                                @if ($estimate->is_current)
                                    <span class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                    Aktualioji sąmata
                                </span>
                                @else
                                    <span class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    Ankstesnė sąmata
                                </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-2">

                                @if ($estimate->is_current && $estimate->status === 'draft')
                                    <form action="{{ route('repair-orders.estimates.send', $estimate->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Ar tikrai norite išsiųsti šią sąmatą klientui?')">
                                        @csrf

                                        <button type="submit"
                                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                                            Siųsti sąmatą klientui
                                        </button>
                                    </form>
                                @endif

                                @if ($repairOrder->status === 'in_repair' && $estimate->is_current)
                                    <form action="{{ route('repair-orders.complete', $repairOrder->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Ar tikrai norite pažymėti remontą kaip užbaigtą?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                                            ✓ Užbaigti remontą
                                        </button>
                                    </form>
                                @endif

                            </div>

                        </div>

                        <div class="overflow-x-auto rounded-lg border border-slate-200">
                            <table class="w-full min-w-[600px] text-left text-sm">

                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Tipas</th>
                                    <th class="px-4 py-3">Aprašymas</th>
                                    <th class="px-4 py-3 text-right">Kiekis</th>
                                    <th class="px-4 py-3 text-right">Vieneto kaina</th>
                                    <th class="px-4 py-3 text-right">Suma</th>
                                </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">

                                @foreach ($estimate->items as $item)
                                    <tr>
                                        <td class="px-4 py-4 text-slate-600">
                                            {{ $item->type }}
                                        </td>

                                        <td class="px-4 py-4 font-medium text-slate-800">
                                            {{ $item->description }}
                                        </td>

                                        <td class="px-4 py-4 text-right text-slate-600">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-4 py-4 text-right text-slate-600">
                                            {{ number_format((float) $item->unit_price, 2) }} €
                                        </td>

                                        <td class="px-4 py-4 text-right font-semibold text-slate-800">
                                            {{ number_format((float) $item->total_price, 2) }} €
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <div class="rounded-lg bg-slate-50 px-5 py-4 text-right">
                                <p class="text-sm text-slate-500">
                                    Bendra sąmatos suma
                                </p>

                                <p class="mt-1 text-2xl font-bold text-slate-800">
                                    {{ number_format((float) $estimate->total_amount, 2) }} €
                                </p>
                            </div>
                        </div>

                    </div>

                    @if (!$loop->last)
                        <hr class="border-slate-200">
                    @endif

                @endforeach

            </div>

        @else

            <div class="py-8 text-center">
                <p class="font-semibold text-slate-700">
                    Sąmata dar nesudaryta.
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Atlikus diagnostiką bus galima sudaryti remonto sąmatą.
                </p>
            </div>

        @endif

        @if (
            $repairOrder->status === 'diagnosing' &&
            $repairOrder->diagnostics->isNotEmpty()
        )
            <div class="mt-6 border-t border-slate-200 pt-5">
                <a href="{{ route('repair-orders.estimates.create', $repairOrder->id) }}"
                   class="inline-flex rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    + Sudaryti sąmatą
                </a>
            </div>
        @endif

    </div>

@endsection
