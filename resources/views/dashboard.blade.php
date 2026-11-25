
@extends('layouts.app')

@section('title', 'Pagrindinis puslapis')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">
            Sveiki, {{ auth()->user()->name }}! 👋
        </h1>

        <p class="mt-2 text-slate-500">
            Sveiki atvykę į autoserviso valdymo sistemą.
        </p>
    </div>

    <div class="mb-8 grid gap-6 md:grid-cols-2">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Jūsų autoservisas
            </p>

            <h2 class="mt-2 text-2xl font-bold text-slate-800">
                {{ auth()->user()->tenant->name }}
            </h2>

            <p class="mt-3 text-sm text-slate-500">
                Autoserviso paskyra, kurioje šiuo metu dirbate.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">
                Jūsų rolė
            </p>

            <div class="mt-3">
            <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-800">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            </div>

            <p class="mt-3 text-sm text-slate-500">
                Jūsų rolė sistemoje nustato prieinamas funkcijas.
            </p>
        </div>

    </div>

    <div class="mb-5">
        <h2 class="text-xl font-bold text-slate-800">
            Greita navigacija
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Pasirinkite sistemos dalį, kurią norite atidaryti.
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

        <a href="{{ route('customers.index') }}"
           class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
            <div class="text-3xl">👥</div>
            <h3 class="mt-4 font-bold text-slate-800">Klientai</h3>
            <p class="mt-2 text-sm text-slate-500">
                Peržiūrėkite klientų informaciją ir jų automobilius.
            </p>
        </a>

        <a href="{{ route('repair-orders.index') }}"
           class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
            <div class="text-3xl">🔧</div>
            <h3 class="mt-4 font-bold text-slate-800">Remonto užsakymai</h3>
            <p class="mt-2 text-sm text-slate-500">
                Peržiūrėkite remonto užklausas ir jų būsenas.
            </p>
        </a>

        <a href="{{ route('inventory.index') }}"
           class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
            <div class="text-3xl">📦</div>
            <h3 class="mt-4 font-bold text-slate-800">Inventorius</h3>
            <p class="mt-2 text-sm text-slate-500">
                Tvarkykite atsargines dalis ir medžiagas.
            </p>
        </a>

    </div>

@endsection
