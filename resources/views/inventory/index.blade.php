@extends('layouts.app')

@section('title', 'Inventorius')

@section('content')

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Autoserviso inventorius
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Atsarginių dalių ir medžiagų valdymas
            </p>
        </div>

        <a href="{{ route('inventory.create') }}"
           class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
            + Pridėti naują prekę
        </a>
    </div>

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="mb-2 font-semibold">Patikrinkite įvestus duomenis:</p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-800">
                Inventoriaus prekės
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Čia pateikiamos visos jūsų autoserviso inventoriaus prekės.
            </p>
        </div>

        @if ($inventoryItems->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="text-lg font-semibold text-slate-700">
                    Inventoriuje prekių dar nėra.
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    Pridėkite pirmą inventoriaus prekę.
                </p>
            </div>
        @else

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Pavadinimas</th>
                        <th class="px-5 py-4">Tipas</th>
                        <th class="px-5 py-4">OEM / AP</th>
                        <th class="px-5 py-4">Likutis</th>
                        <th class="px-5 py-4">Min. kiekis</th>
                        <th class="px-5 py-4">Vienetas</th>
                        <th class="px-5 py-4">Pirkimo kaina</th>
                        <th class="px-5 py-4">Būsena</th>
                        <th class="px-5 py-4">Veiksmai</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                    @foreach ($inventoryItems as $item)
                        <tr class="align-top transition hover:bg-slate-50">

                            <td class="px-5 py-5">
                                <p class="font-semibold text-slate-800">
                                    {{ $item->name }}
                                </p>

                                <a href="{{ route('inventory.show', $item->id) }}"
                                   class="mt-2 inline-block text-xs font-medium text-blue-600 hover:text-blue-800">
                                    Peržiūrėti judėjimų istoriją →
                                </a>
                            </td>

                            <td class="px-5 py-5">
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                    {{ $item->type === 'part' ? 'Atsarginė dalis' : 'Medžiaga' }}
                                </span>
                            </td>

                            <td class="px-5 py-5 text-slate-600">
                                <p>OEM: {{ $item->oem_number ?? '-' }}</p>
                                <p class="mt-1">AP: {{ $item->ap_number ?? '-' }}</p>
                            </td>

                            <td class="px-5 py-5 font-semibold text-slate-800">
                                {{ $item->quantity }}
                            </td>

                            <td class="px-5 py-5 text-slate-600">
                                {{ $item->minimum_quantity }}
                            </td>

                            <td class="px-5 py-5 text-slate-600">
                                {{ $item->unit }}
                            </td>

                            <td class="px-5 py-5 text-slate-700">
                                {{ number_format((float) $item->purchase_price, 2, ',', ' ') }} €
                            </td>

                            <td class="px-5 py-5">
                                @if ((float) $item->quantity <= (float) $item->minimum_quantity)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Reikia papildyti
                                    </span>
                                @else
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Pakanka
                                    </span>
                                @endif
                            </td>

                            <td class="w-80 px-5 py-5">

                                <div class="mb-5 rounded-lg border border-slate-200 p-4">
                                    <h3 class="mb-3 font-semibold text-slate-800">
                                        Papildyti atsargas
                                    </h3>

                                    <form action="{{ route('inventory.receive', $item->id) }}"
                                          method="POST"
                                          class="space-y-3">
                                        @csrf

                                        <input
                                            type="number"
                                            name="quantity"
                                            min="0.01"
                                            step="0.01"
                                            placeholder="Kiekis"
                                            required
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                        >

                                        <input
                                            type="text"
                                            name="notes"
                                            placeholder="Pastaba (nebūtina)"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                        >

                                        <button type="submit"
                                                class="w-full rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition hover:bg-blue-700">
                                            Papildyti
                                        </button>
                                    </form>
                                </div>

                                <div class="rounded-lg border border-slate-200 p-4">
                                    <h3 class="mb-3 font-semibold text-slate-800">
                                        Panaudoti remontui
                                    </h3>

                                    @if ($repairOrders->isNotEmpty())

                                        <form action="{{ route('inventory.consume', $item->id) }}"
                                              method="POST"
                                              class="space-y-3">
                                            @csrf

                                            <select
                                                name="repair_order_id"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                            >
                                                <option value="">Pasirinkite remonto užsakymą</option>

                                                @foreach ($repairOrders as $order)
                                                    <option value="{{ $order->id }}">
                                                        #{{ $order->id }} –
                                                        {{ $order->customerVehicle->customer->name }} –
                                                        {{ $order->customerVehicle->vehicle->make }}
                                                        {{ $order->customerVehicle->vehicle->model }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <input
                                                type="number"
                                                name="quantity"
                                                min="0.01"
                                                step="0.01"
                                                placeholder="Kiekis"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                            >

                                            <input
                                                type="text"
                                                name="notes"
                                                placeholder="Pastaba (nebūtina)"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                            >

                                            <button type="submit"
                                                    class="w-full rounded-lg bg-emerald-600 px-4 py-2 font-medium text-white transition hover:bg-emerald-700">
                                                Registruoti panaudojimą
                                            </button>
                                        </form>

                                    @else
                                        <p class="text-sm text-slate-500">
                                            Nėra vykdomų remonto užsakymų.
                                        </p>
                                    @endif
                                </div>

                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $inventoryItems->links() }}
            </div>

        @endif

    </div>

@endsection
