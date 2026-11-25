
@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Remonto sąmatos kūrimas
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Užsakymas #{{ $repairOrder->id }}
                </p>
            </div>

            <a href="{{ route('repair-orders.show', $repairOrder->id) }}"
               class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                ← Grįžti į remonto užsakymą
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h2 class="text-lg font-semibold text-slate-800 mb-3">
                Užsakymo informacija
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Klientas</p>
                    <p class="font-medium text-slate-800">
                        {{ $repairOrder->customerVehicle->customer->name }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-500">Automobilis</p>
                    <p class="font-medium text-slate-800">
                        {{ $repairOrder->customerVehicle->vehicle->make }}
                        {{ $repairOrder->customerVehicle->vehicle->model }}
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                <p class="font-semibold mb-2">Nepavyko išsaugoti sąmatos:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('repair-orders.estimates.store', $repairOrder->id) }}"
              method="POST"
              class="space-y-5">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Sąmatos eilutės
                    </h2>
                    <span class="text-xs text-slate-500">
                    Kainos nurodomos eurais
                </span>
                </div>

                <div id="estimate-items" class="space-y-5">

                    <div class="estimate-item border border-slate-200 rounded-lg p-4 space-y-4">
                        <h3 class="font-semibold text-slate-800">
                            1 eilutė
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Inventoriaus prekė (nebūtina)
                            </label>

                            <select class="inventory-select w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onchange="fillInventoryItem(this)">
                                <option value="">-- Įvesti rankiniu būdu --</option>

                                @foreach ($inventoryItems as $inventoryItem)
                                    <option
                                        value="{{ $inventoryItem->id }}"
                                        data-name="{{ $inventoryItem->name }}"
                                        data-type="{{ $inventoryItem->type }}"
                                        data-oem="{{ $inventoryItem->oem_number }}"
                                        data-ap="{{ $inventoryItem->ap_number }}"
                                    >
                                        {{ $inventoryItem->name }}
                                        @if ($inventoryItem->oem_number)
                                            (OEM: {{ $inventoryItem->oem_number }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden"
                                   name="items[0][inventory_item_id]"
                                   value="">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Tipas
                                </label>
                                <select name="items[0][type]" required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="labor">Darbas</option>
                                    <option value="part">Detalė</option>
                                    <option value="material">Medžiaga</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Aprašymas
                                </label>
                                <input type="text"
                                       name="items[0][description]"
                                       required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    OEM kodas
                                </label>
                                <input type="text"
                                       name="items[0][oem_number]"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Alternatyvus detalės kodas
                                </label>
                                <input type="text"
                                       name="items[0][ap_number]"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Kiekis
                                </label>
                                <input type="number"
                                       name="items[0][quantity]"
                                       min="0.01"
                                       step="0.01"
                                       value="1"
                                       required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Vieneto pardavimo kaina klientui (€)
                                </label>
                                <input type="number"
                                       name="items[0][unit_price]"
                                       min="0"
                                       step="0.01"
                                       required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>

                </div>

                <button type="button"
                        onclick="addItem()"
                        class="mt-5 inline-flex items-center px-4 py-2 rounded-lg border border-blue-300 text-blue-700 text-sm font-medium hover:bg-blue-50">
                    + Pridėti eilutę
                </button>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                    Išsaugoti sąmatą
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = 1;

        const inventoryOptions = `
        @foreach ($inventoryItems as $inventoryItem)
        <option
            value="{{ $inventoryItem->id }}"
                data-name="{{ e($inventoryItem->name) }}"
                data-type="{{ $inventoryItem->type }}"
                data-oem="{{ e($inventoryItem->oem_number ?? '') }}"
                data-ap="{{ e($inventoryItem->ap_number ?? '') }}"
            >
                {{ $inventoryItem->name }}
        @if ($inventoryItem->oem_number)
        (OEM: {{ $inventoryItem->oem_number }})
                @endif
        </option>
@endforeach
        `;

        function fillInventoryItem(select) {
            const item = select.closest('.estimate-item');
            const option = select.options[select.selectedIndex];
            const inventoryId = select.value;

            item.querySelector('input[name$="[inventory_item_id]"]').value = inventoryId;

            if (!inventoryId) {
                return;
            }

            item.querySelector('input[name$="[description]"]').value =
                option.dataset.name || '';

            item.querySelector('input[name$="[oem_number]"]').value =
                option.dataset.oem || '';

            item.querySelector('input[name$="[ap_number]"]').value =
                option.dataset.ap || '';

            item.querySelector('select[name$="[type]"]').value =
                option.dataset.type || 'part';
        }

        function addItem() {
            const container = document.getElementById('estimate-items');
            const item = document.createElement('div');

            item.className = 'estimate-item border border-slate-200 rounded-lg p-4 space-y-4';

            item.innerHTML = `
            <h3 class="font-semibold text-slate-800">
                ${itemIndex + 1} eilutė
            </h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Inventoriaus prekė (nebūtina)
                </label>

                <select class="inventory-select w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                        onchange="fillInventoryItem(this)">
                    <option value="">-- Įvesti rankiniu būdu --</option>
                    ${inventoryOptions}
                </select>

                <input type="hidden"
                       name="items[${itemIndex}][inventory_item_id]"
                       value="">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tipas
                    </label>
                    <select name="items[${itemIndex}][type]" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="labor">Darbas</option>
                        <option value="part">Detalė</option>
                        <option value="material">Medžiaga</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Aprašymas
                    </label>
                    <input type="text"
                           name="items[${itemIndex}][description]"
                           required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        OEM kodas
                    </label>
                    <input type="text"
                           name="items[${itemIndex}][oem_number]"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Alternatyvus detalės kodas
                    </label>
                    <input type="text"
                           name="items[${itemIndex}][ap_number]"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Kiekis
                    </label>
                    <input type="number"
                           name="items[${itemIndex}][quantity]"
                           min="0.01"
                           step="0.01"
                           value="1"
                           required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Vieneto pardavimo kaina klientui (€)
                    </label>
                    <input type="number"
                           name="items[${itemIndex}][unit_price]"
                           min="0"
                           step="0.01"
                           required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
        `;

            container.appendChild(item);
            itemIndex++;
        }
    </script>
@endsection
