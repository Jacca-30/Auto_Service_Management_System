
@extends('layouts.app')

@section('title', 'Pridėti automobilį')

@section('content')

    <div class="mb-8">
        <a href="{{ route('customers.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-800">
            ← Grįžti į klientų sąrašą
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-800">
            Pridėti automobilį
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Klientas:
            <span class="font-semibold text-slate-700">{{ $customer->name }}</span>
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="mb-2 font-semibold">Nepavyko išsaugoti automobilio:</p>

            <ul class="list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <form method="POST" action="{{ route('customers.vehicles.store', $customer->id) }}" class="space-y-8">
            @csrf

            <section>
                <div class="mb-5 border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-bold text-slate-800">
                        🚗 Automobilio techniniai duomenys
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Įveskite pagrindinę informaciją apie automobilį.
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div>
                        <label for="make" class="mb-2 block text-sm font-semibold text-slate-700">
                            Markė *
                        </label>
                        <input type="text" id="make" name="make"
                               value="{{ old('make') }}" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., BMW">
                        @error('make')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="mb-2 block text-sm font-semibold text-slate-700">
                            Modelis *
                        </label>
                        <input type="text" id="model" name="model"
                               value="{{ old('model') }}" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., 530d">
                        @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="mb-2 block text-sm font-semibold text-slate-700">
                            Pagaminimo metai
                        </label>
                        <input type="number" id="year" name="year"
                               value="{{ old('year') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., 2018">
                        @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fuel_type" class="mb-2 block text-sm font-semibold text-slate-700">
                            Kuro tipas
                        </label>
                        <input type="text" id="fuel_type" name="fuel_type"
                               value="{{ old('fuel_type') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., Dyzelinas">
                        @error('fuel_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="engine_type" class="mb-2 block text-sm font-semibold text-slate-700">
                            Variklio tipas
                        </label>
                        <input type="text" id="engine_type" name="engine_type"
                               value="{{ old('engine_type') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., R6">
                        @error('engine_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="engine_displacement_cm3" class="mb-2 block text-sm font-semibold text-slate-700">
                            Variklio darbinis tūris (cm³)
                        </label>
                        <input type="number" id="engine_displacement_cm3"
                               name="engine_displacement_cm3"
                               value="{{ old('engine_displacement_cm3') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., 2993">
                        @error('engine_displacement_cm3')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="engine_power_kw" class="mb-2 block text-sm font-semibold text-slate-700">
                            Variklio galia (kW)
                        </label>
                        <input type="number" id="engine_power_kw" name="engine_power_kw"
                               value="{{ old('engine_power_kw') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., 195">
                        @error('engine_power_kw')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="transmission_type" class="mb-2 block text-sm font-semibold text-slate-700">
                            Pavarų dėžė
                        </label>
                        <input type="text" id="transmission_type" name="transmission_type"
                               value="{{ old('transmission_type') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., Automatinė">
                        @error('transmission_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            <section>
                <div class="mb-5 border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-bold text-slate-800">
                        📋 Registracijos duomenys
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Automobilio identifikavimo ir registracijos informacija.
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">

                    <div>
                        <label for="vin" class="mb-2 block text-sm font-semibold text-slate-700">
                            VIN kodas
                        </label>
                        <input type="text" id="vin" name="vin" maxlength="17"
                               value="{{ old('vin') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 uppercase outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="17 simbolių VIN kodas">
                        @error('vin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_plate" class="mb-2 block text-sm font-semibold text-slate-700">
                            Valstybinis numeris
                        </label>
                        <input type="text" id="license_plate" name="license_plate"
                               value="{{ old('license_plate') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 uppercase outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Pvz., ABC123">
                        @error('license_plate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="reg_cert_number" class="mb-2 block text-sm font-semibold text-slate-700">
                            Registracijos liudijimo numeris
                        </label>
                        <input type="text" id="reg_cert_number" name="reg_cert_number"
                               value="{{ old('reg_cert_number') }}"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Registracijos liudijimo numeris">
                        @error('reg_cert_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6">

                <a href="{{ route('customers.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Atšaukti
                </a>

                <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Išsaugoti automobilį
                </button>

            </div>

        </form>

    </div>

@endsection
