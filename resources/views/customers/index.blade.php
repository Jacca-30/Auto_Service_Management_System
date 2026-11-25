
@extends('layouts.app')

@section('title', 'Klientai')

@section('content')

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Klientai
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Autoservisas: {{ auth()->user()->tenant->name }}
            </p>
        </div>

        <a href="{{ route('customers.create') }}"
           class="rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700">
            + Pridėti klientą
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-bold text-slate-800">
                Klientų sąrašas
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Registruoti autoserviso klientai ir jų kontaktinė informacija.
            </p>
        </div>

        @if ($customers->isEmpty())

            <div class="px-6 py-12 text-center">
                <p class="text-lg font-semibold text-slate-700">
                    Klientų dar nėra.
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    Pridėkite pirmą klientą į sistemą.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Vardas, pavardė</th>
                        <th class="px-6 py-4">El. paštas</th>
                        <th class="px-6 py-4">Telefono numeris</th>
                        <th class="px-6 py-4">Veiksmai</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                    @foreach ($customers as $customer)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-5">
                                <a href="{{ route('customers.show', $customer->id) }}"
                                   class="font-semibold text-blue-600 hover:text-blue-800">
                                    {{ $customer->name }}
                                </a>
                            </td>

                            <td class="px-6 py-5 text-slate-600">
                                {{ $customer->email ?? '-' }}
                            </td>

                            <td class="px-6 py-5 text-slate-600">
                                {{ $customer->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-5">
                                <a href="{{ route('customers.vehicles.create', $customer->id) }}"
                                   class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-2 font-medium text-blue-700 transition hover:bg-blue-100">
                                    + Pridėti automobilį
                                </a>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $customers->links() }}
            </div>

        @endif

    </div>

@endsection
