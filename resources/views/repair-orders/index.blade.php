
@extends('layouts.app')

@section('title', 'Remonto užsakymai')

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
        <h1 class="text-3xl font-bold text-slate-800">
            Remonto užsakymai
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Autoserviso gautos remonto užklausos ir jų vykdymo eiga.
        </p>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-800">
                Užsakymų sąrašas
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Peržiūrėkite klientų automobilius ir remonto būsenas.
            </p>
        </div>

        @if ($repairOrders->isEmpty())

            <div class="px-6 py-12 text-center">
                <p class="text-lg font-semibold text-slate-700">
                    Remonto užsakymų kol kas nėra.
                </p>

                <p class="mt-2 text-sm text-slate-500">
                    Kai klientai pateiks remonto užklausas, jos atsiras čia.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Nr.</th>
                        <th class="px-5 py-4">Klientas</th>
                        <th class="px-5 py-4">Automobilis</th>
                        <th class="px-5 py-4">Valstybinis numeris</th>
                        <th class="px-5 py-4">Gedimo aprašymas</th>
                        <th class="px-5 py-4">Statusas</th>
                        <th class="px-5 py-4">Data</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                    @foreach ($repairOrders as $order)
                        <tr class="align-top transition hover:bg-slate-50">

                            <td class="px-5 py-5">
                                <a href="{{ route('repair-orders.show', $order->id) }}"
                                   class="font-bold text-blue-600 hover:text-blue-800">
                                    #{{ $order->id }}
                                </a>
                            </td>

                            <td class="px-5 py-5 font-medium text-slate-800">
                                {{ $order->customerVehicle->customer->name }}
                            </td>

                            <td class="px-5 py-5 text-slate-700">
                                {{ $order->customerVehicle->vehicle->make }}
                                {{ $order->customerVehicle->vehicle->model }}
                            </td>

                            <td class="px-5 py-5">
                                <span class="rounded-md bg-slate-100 px-3 py-1 font-medium text-slate-700">
                                    {{ $order->customerVehicle->license_plate }}
                                </span>
                            </td>

                            <td class="max-w-xs whitespace-normal px-5 py-5 text-slate-600">
                                {{ $order->customer_complaint }}
                            </td>

                            <td class="px-5 py-5">
                                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-5 text-slate-500">
                                {{ $order->created_at->format('Y-m-d H:i') }}
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $repairOrders->links() }}
            </div>

        @endif

    </div>

@endsection
