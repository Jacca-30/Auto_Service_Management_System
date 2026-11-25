
@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Prekės judėjimų istorija
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Inventoriaus likučio papildymo ir panaudojimo įrašai
                </p>
            </div>

            <a href="{{ route('inventory.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                ← Grįžti į inventorių
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:col-span-2">
                <p class="text-sm text-slate-500 mb-1">Prekės pavadinimas</p>
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $item->name }}
                </h2>

                <div class="flex flex-wrap gap-2 mt-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    {{ $item->type === 'part' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                    {{ $item->type === 'part' ? 'Atsarginė dalis' : 'Medžiaga' }}
                </span>

                    @if ($item->oem_number)
                        <span class="text-xs text-slate-500 bg-slate-100 rounded-full px-3 py-1">
                        OEM: {{ $item->oem_number }}
                    </span>
                    @endif

                    @if ($item->ap_number)
                        <span class="text-xs text-slate-500 bg-slate-100 rounded-full px-3 py-1">
                        AP: {{ $item->ap_number }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <p class="text-sm text-slate-500 mb-2">Esamas likutis</p>

                <p class="text-3xl font-bold tabular-nums
                {{ (float) $item->quantity <= (float) $item->minimum_quantity ? 'text-red-600' : 'text-emerald-600' }}">
                    {{ $item->quantity }}
                </p>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $item->unit }}
                </p>

                <p class="text-xs text-slate-400 mt-3">
                    Minimalus kiekis: {{ $item->minimum_quantity }} {{ $item->unit }}
                </p>
            </div>

        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-5 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-800">
                    Judėjimų istorija
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Visi šios prekės inventoriaus judėjimai
                </p>
            </div>

            @if ($movements->isEmpty())

                <div class="p-10 text-center">
                    <p class="text-slate-600 font-medium">
                        Ši prekė dar neturi judėjimų istorijos.
                    </p>
                    <p class="text-sm text-slate-400 mt-1">
                        Papildžius atsargas arba panaudojus prekę remontui, įrašai atsiras čia.
                    </p>
                </div>

            @else

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Data</th>
                            <th class="px-5 py-3 font-semibold">Veiksmas</th>
                            <th class="px-5 py-3 font-semibold">Kiekis</th>
                            <th class="px-5 py-3 font-semibold">Remonto užsakymas</th>
                            <th class="px-5 py-3 font-semibold">Atliko</th>
                            <th class="px-5 py-3 font-semibold">Pastaba</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                        @foreach ($movements as $movement)
                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4 whitespace-nowrap text-slate-600">
                                    {{ $movement->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($movement->type === 'received')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Atsargos papildytos
                                        </span>
                                    @elseif ($movement->type === 'used')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                            Panaudota remontui
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            Likučio koregavimas
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                    {{ $movement->quantity }} {{ $item->unit }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $movement->repair_order_id
                                        ? '#' . $movement->repair_order_id
                                        : '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $movement->performedBy->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $movement->notes ?? '-' }}
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-slate-200">
                    {{ $movements->links() }}
                </div>

            @endif

        </div>

    </div>
@endsection
