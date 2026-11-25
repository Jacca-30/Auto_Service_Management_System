
@extends('layouts.app')

@section('title', 'Kliento informacija')

@section('content')

    <div class="mb-8">
        <a href="{{ route('customers.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-800">
            ← Grįžti į klientų sąrašą
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-800">
            Kliento informacija
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Kliento kontaktiniai duomenys ir registruoti automobiliai.
        </p>
    </div>

    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-xl font-bold text-slate-800">
            Kontaktiniai duomenys
        </h2>

        <div class="grid gap-6 sm:grid-cols-3">

            <div>
                <p class="text-sm text-slate-500">Vardas ir pavardė</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $customer->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">El. paštas</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $customer->email ?? 'Nenurodyta' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Telefonas</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $customer->phone ?? 'Nenurodyta' }}
                </p>
            </div>

        </div>
    </div>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-4">

        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Kliento automobiliai
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Klientui priskirtų automobilių sąrašas.
            </p>
        </div>

        <a href="{{ route('customers.vehicles.create', $customer->id) }}"
           class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            + Pridėti automobilį
        </a>

    </div>

    @if ($customer->customerVehicles->isEmpty())

        <div class="rounded-xl border border-slate-200 bg-white px-6 py-12 text-center shadow-sm">
            <div class="text-4xl">🚗</div>

            <p class="mt-4 text-lg font-semibold text-slate-700">
                Šis klientas dar neturi automobilių.
            </p>

            <p class="mt-2 text-sm text-slate-500">
                Pridėkite automobilį, kad galėtumėte registruoti remonto užsakymus.
            </p>
        </div>

    @else

        <div class="grid gap-6 lg:grid-cols-2">

            @foreach ($customer->customerVehicles as $customerVehicle)

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                        <h3 class="text-xl font-bold text-slate-800">
                            🚗 {{ $customerVehicle->vehicle->make }}
                            {{ $customerVehicle->vehicle->model }}
                        </h3>

                        <p class="mt-2 text-sm font-medium text-slate-500">
                            Valstybinis numeris:
                            <span class="text-slate-800">
                            {{ $customerVehicle->license_plate ?? 'Nenurodyta' }}
                        </span>
                        </p>

                    </div>

                    <div class="grid gap-5 p-6 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Pagaminimo metai</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->year ?? 'Nenurodyta' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Kuro tipas</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->fuel_type ?? 'Nenurodyta' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Variklio kodas</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->engine_code ?? 'Nenurodyta' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Darbinis tūris</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->engine_displacement_cm3 ?? 'Nenurodyta' }}
                                @if ($customerVehicle->vehicle->engine_displacement_cm3)
                                    cm³
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Galia</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->engine_power_kw ?? 'Nenurodyta' }}
                                @if ($customerVehicle->vehicle->engine_power_kw)
                                    kW
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Pavarų dėžė</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->vehicle->transmission_type ?? 'Nenurodyta' }}
                            </p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium uppercase text-slate-400">VIN kodas</p>
                            <p class="mt-1 break-all font-semibold text-slate-800">
                                {{ $customerVehicle->vin ?? 'Nenurodyta' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-slate-400">Registracijos liudijimo numeris</p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $customerVehicle->reg_cert_number ?? 'Nenurodyta' }}
                            </p>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

@endsection
