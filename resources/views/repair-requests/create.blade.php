<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remonto užklausa – {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 py-10 px-4">

<main class="max-w-4xl mx-auto">

    <header class="text-center mb-8">
        <p class="text-sm font-semibold uppercase tracking-wider text-blue-600 mb-2">
            Remonto užklausa
        </p>
        <h1 class="text-3xl font-bold text-slate-800">
            {{ $tenant->name }}
        </h1>
        <p class="text-slate-500 mt-3">
            Užpildykite formą ir susisieksime su jumis.
        </p>
    </header>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            <strong>Patikrinkite įvestus duomenis:</strong>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('repair-requests.store', $tenant->slug) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Kliento duomenys --}}
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-5">
                1. Kliento duomenys
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Vardas ir pavardė *
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        El. paštas *
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">
                        Telefono numeris *
                    </label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
            </div>
        </section>

        {{-- Automobilio duomenys --}}
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-5">
                2. Automobilio duomenys
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="make" class="block text-sm font-medium text-slate-700 mb-1">
                        Markė *
                    </label>
                    <input type="text" id="make" name="make"
                           value="{{ old('make') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-slate-700 mb-1">
                        Modelis *
                    </label>
                    <input type="text" id="model" name="model"
                           value="{{ old('model') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-slate-700 mb-1">
                        Pagaminimo metai *
                    </label>
                    <input type="number" id="year" name="year"
                           value="{{ old('year') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-slate-700 mb-1">
                        Kuro tipas *
                    </label>
                    <input type="text" id="fuel_type" name="fuel_type"
                           value="{{ old('fuel_type') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="engine_code" class="block text-sm font-medium text-slate-700 mb-1">
                        Variklio kodas
                    </label>
                    <input type="text" id="engine_code" name="engine_code"
                           value="{{ old('engine_code') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="engine_displacement_cm3" class="block text-sm font-medium text-slate-700 mb-1">
                        Variklio darbinis tūris (cm³)
                    </label>
                    <input type="number" id="engine_displacement_cm3"
                           name="engine_displacement_cm3"
                           value="{{ old('engine_displacement_cm3') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="engine_power_kw" class="block text-sm font-medium text-slate-700 mb-1">
                        Variklio galia (kW)
                    </label>
                    <input type="number" id="engine_power_kw" name="engine_power_kw"
                           value="{{ old('engine_power_kw') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="transmission_type" class="block text-sm font-medium text-slate-700 mb-1">
                        Pavarų dėžė *
                    </label>
                    <input type="text" id="transmission_type" name="transmission_type"
                           value="{{ old('transmission_type') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="vin" class="block text-sm font-medium text-slate-700 mb-1">
                        VIN kodas
                    </label>
                    <input type="text" id="vin" name="vin"
                           value="{{ old('vin') }}" maxlength="17"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="license_plate" class="block text-sm font-medium text-slate-700 mb-1">
                        Valstybinis numeris *
                    </label>
                    <input type="text" id="license_plate" name="license_plate"
                           value="{{ old('license_plate') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>

                <div>
                    <label for="reg_cert_number" class="block text-sm font-medium text-slate-700 mb-1">
                        Registracijos liudijimo numeris
                    </label>
                    <input type="text" id="reg_cert_number" name="reg_cert_number"
                           value="{{ old('reg_cert_number') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
            </div>
        </section>

        {{-- Gedimo informacija --}}
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-semibold text-slate-800 mb-5">
                3. Informacija apie gedimą
            </h2>

            <div>
                <label for="customer_complaint" class="block text-sm font-medium text-slate-700 mb-1">
                    Aprašykite automobilio problemą *
                </label>
                <textarea id="customer_complaint" name="customer_complaint"
                          rows="5" required
                          class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                          placeholder="Kada pastebėjote gedimą? Kokie požymiai pasireiškia?">{{ old('customer_complaint') }}</textarea>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit"
                    class="w-full sm:w-auto rounded-lg bg-blue-600 px-8 py-3 font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                Pateikti užklausą
            </button>
        </div>

    </form>

    <p class="text-center text-sm text-slate-400 mt-8">
        {{ $tenant->name }}
    </p>

</main>

</body>
</html>
