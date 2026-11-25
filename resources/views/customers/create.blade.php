
@extends('layouts.app')

@section('title', 'Naujas klientas')

@section('content')

    <div class="mb-8">
        <a href="{{ route('customers.index') }}"
           class="text-sm font-medium text-blue-600 hover:text-blue-800">
            ← Grįžti į klientų sąrašą
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-800">
            Naujo kliento registravimas
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Autoservisas: {{ auth()->user()->tenant->name }}
        </p>
    </div>

    <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-6 border-b border-slate-200 pb-5">
            <h2 class="text-xl font-bold text-slate-800">
                Kliento informacija
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Užpildykite kliento kontaktinius duomenis.
            </p>
        </div>

        <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                    Vardas ir pavardė
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Pvz., Jonas Jonaitis"
                >

                @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                    El. paštas
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Pvz., jonas@email.com"
                >

                @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">
                    Telefono numeris
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Pvz., +370 600 00000"
                >

                @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-200 pt-5">
                <a href="{{ route('customers.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Atšaukti
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    Išsaugoti klientą
                </button>
            </div>
        </form>

    </div>

@endsection
