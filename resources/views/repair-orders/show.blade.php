
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remonto užsakymas #{{ $repairOrder->id }}</title>
</head>
<body>

<h1>Remonto užsakymas #{{ $repairOrder->id }}</h1>

<p>
    <a href="{{ route('repair-orders.index') }}">
        ← Grįžti į užsakymų sąrašą
    </a>
</p>

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h2>Užsakymo informacija</h2>

<p><strong>Būsena:</strong> {{ $repairOrder->status }}</p>

@if ($repairOrder->status === 'pending')
    <form action="{{ route('repair-orders.accept', $repairOrder->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit">Priimti remonto užklausą</button>
    </form>

    <form action="{{ route('repair-orders.decline', $repairOrder->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit"
                onclick="return confirm('Ar tikrai norite atmesti šią remonto užklausą?')">
            Atmesti remonto užklausą
        </button>
    </form>
@endif

<p>
    <strong>Užklausos data:</strong>
    {{ $repairOrder->created_at->format('Y-m-d H:i') }}
</p>

<h2>Kliento informacija</h2>

<p><strong>Vardas:</strong> {{ $repairOrder->customerVehicle->customer->name }}</p>
<p><strong>El. paštas:</strong> {{ $repairOrder->customerVehicle->customer->email }}</p>
<p><strong>Telefonas:</strong> {{ $repairOrder->customerVehicle->customer->phone }}</p>

<h2>Automobilio informacija</h2>

<p>
    <strong>Markė ir modelis:</strong>
    {{ $repairOrder->customerVehicle->vehicle->make }}
    {{ $repairOrder->customerVehicle->vehicle->model }}
</p>

<p>
    <strong>Pagaminimo metai:</strong>
    {{ $repairOrder->customerVehicle->vehicle->year ?? 'Nenurodyta' }}
</p>

<p>
    <strong>Valstybinis numeris:</strong>
    {{ $repairOrder->customerVehicle->license_plate ?? 'Nenurodyta' }}
</p>

<p>
    <strong>VIN:</strong>
    {{ $repairOrder->customerVehicle->vin ?? 'Nenurodyta' }}
</p>

<h2>Kliento nurodytas gedimas</h2>
<p>{{ $repairOrder->customer_complaint }}</p>

<h2>Vidinės pastabos</h2>
<p>{{ $repairOrder->internal_notes ?? 'Pastabų nėra.' }}</p>

<hr>

<h2>Diagnostika</h2>

@if ($repairOrder->diagnostics->isNotEmpty())

    @foreach ($repairOrder->diagnostics as $diagnostic)
        <h3>Diagnostika #{{ $diagnostic->id }}</h3>

        <p><strong>Nustatyti gedimai:</strong></p>
        <p>{{ $diagnostic->findings }}</p>

        <p><strong>Rekomenduojami remonto darbai:</strong></p>
        <p>{{ $diagnostic->recommendations ?? 'Rekomendacijų nėra.' }}</p>

        <p>
            <strong>Diagnostiką atliko:</strong>
            {{ $diagnostic->performedBy->name ?? 'Nenurodyta' }}
        </p>

        <p>
            <strong>Data:</strong>
            {{ $diagnostic->created_at->format('Y-m-d H:i') }}
        </p>
    @endforeach

@else

    @if (in_array($repairOrder->status, ['accepted', 'diagnosing']))
        <h3>Diagnostikos atlikimas</h3>

        <form action="{{ route('repair-orders.diagnostics.store', $repairOrder->id) }}" method="POST">
            @csrf

            <div>
                <label for="findings">Nustatyti gedimai:</label><br>
                <textarea id="findings" name="findings" rows="5" cols="50" required>{{ old('findings') }}</textarea>
            </div>

            <br>

            <div>
                <label for="recommendations">Rekomenduojami remonto darbai:</label><br>
                <textarea id="recommendations" name="recommendations" rows="5" cols="50">{{ old('recommendations') }}</textarea>
            </div>

            <br>

            <button type="submit">Išsaugoti diagnostiką</button>
        </form>
    @else
        <p>Diagnostika dar neatlikta.</p>
    @endif

@endif

<hr>

<h2>Sąmatos</h2>

@if ($repairOrder->estimates->isNotEmpty())

    @foreach ($repairOrder->estimates->sortByDesc('version') as $estimate)
        <h3>
            Sąmata Nr. {{ $estimate->version }}
            @if ($estimate->is_current)
                (Aktuali)
            @else
                (Ankstesnė)
            @endif
        </h3>

        @if ($repairOrder->status === 'in_repair')
            <form
                action="{{ route('repair-orders.complete', $repairOrder->id) }}"
                method="POST"
                onsubmit="return confirm('Ar tikrai norite pažymėti remontą kaip užbaigtą?')"
            >
                @csrf
                @method('PATCH')

                @if ($estimate->is_current)
                    <button type="submit">Užbaigti remontą</button>
                @endif
            </form>
        @endif

        @if ($estimate->is_current && $estimate->status === 'draft')
            <form
                action="{{ route('repair-orders.estimates.send', $estimate->id) }}"
                method="POST"
                onsubmit="return confirm('Ar tikrai norite išsiųsti šią sąmatą klientui?')"
            >
                @csrf

                <button type="submit">
                    Siųsti sąmatą klientui
                </button>
            </form>
        @endif

        <table border="1" cellpadding="8">
            <thead>
            <tr>
                <th>Tipas</th>
                <th>Aprašymas</th>
                <th>Kiekis</th>
                <th>Vieneto kaina</th>
                <th>Suma</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($estimate->items as $item)
                <tr>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->unit_price, 2) }} €</td>
                    <td>{{ number_format((float) $item->total_price, 2) }} €</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <p>
            <strong>
                Bendra suma:
                {{ number_format((float) $estimate->total_amount, 2) }} €
            </strong>
        </p>

        <hr>
    @endforeach

@else
    <p>Sąmata dar nesudaryta.</p>
@endif

@if (
    $repairOrder->status === 'diagnosing' &&
    $repairOrder->diagnostics->isNotEmpty()
)
    <a href="{{ route('repair-orders.estimates.create', $repairOrder->id) }}">
        + Sudaryti sąmatą
    </a>
@endif

</body>
</html>
