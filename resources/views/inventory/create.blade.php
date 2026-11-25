
@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Naujos inventoriaus prekės pridėjimas
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Užpildykite informaciją apie naują prekę.
                </p>
            </div>

            <a href="{{ route('inventory.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                ← Grįžti į inventorių
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
                <p class="font-semibold mb-2">Patikrinkite įvestus duomenis:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('inventory.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-1">
                        Prekės tipas
                    </label>
                    <select id="type" name="type" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pasirinkite</option>
                        <option value="part" {{ old('type') === 'part' ? 'selected' : '' }}>
                            Atsarginė dalis
                        </option>
                        <option value="material" {{ old('type') === 'material' ? 'selected' : '' }}>
                            Medžiaga
                        </option>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Pavadinimas
                    </label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}" required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="oem_number" class="block text-sm font-medium text-slate-700 mb-1">
                            OEM numeris
                        </label>
                        <input id="oem_number" type="text" name="oem_number"
                               value="{{ old('oem_number') }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="ap_number" class="block text-sm font-medium text-slate-700 mb-1">
                            AP numeris
                        </label>
                        <input id="ap_number" type="text" name="ap_number"
                               value="{{ old('ap_number') }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-slate-700 mb-1">
                        Matavimo vienetas
                    </label>
                    <input id="unit" type="text" name="unit"
                           value="{{ old('unit', 'vnt.') }}" required
                           placeholder="Pvz., vnt., l, kg"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">
                        Nurodykite, kokiais vienetais skaičiuojamas prekės kiekis.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="minimum_quantity" class="block text-sm font-medium text-slate-700 mb-1">
                            Minimalus kiekis
                        </label>
                        <input id="minimum_quantity" type="number"
                               name="minimum_quantity"
                               value="{{ old('minimum_quantity', 0) }}"
                               min="0" step="0.01" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-slate-700 mb-1">
                            Pirkimo kaina (€)
                        </label>
                        <input id="purchase_price" type="number"
                               name="purchase_price"
                               value="{{ old('purchase_price', 0) }}"
                               min="0" step="0.01" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm">
                        Išsaugoti prekę
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
