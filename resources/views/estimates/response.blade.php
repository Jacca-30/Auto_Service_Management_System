
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąmatos peržiūra</title>
</head>
<body>

<h1>Automobilio remonto sąmata</h1>

<p>
    Sveiki, {{ $estimate->repairOrder->customerVehicle->customer->name }}!
</p>

<p>
    Peržiūrėkite autoserviso parengtą remonto sąmatą.
</p>

<h2>Automobilio informacija</h2>

<p>
    <strong>Markė ir modelis:</strong>
    {{ $estimate->repairOrder->customerVehicle->vehicle->make }}
    {{ $estimate->repairOrder->customerVehicle->vehicle->model }}
</p>

<p>
    <strong>Valstybinis numeris:</strong>
    {{ $estimate->repairOrder->customerVehicle->license_plate ?? 'Nenurodyta' }}
</p>

<h2>Sąmatos eilutės</h2>

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

<h2>
    Bendra suma:
    {{ number_format((float) $estimate->total_amount, 2) }} €
</h2>

<p>
    Sąmata galioja iki:
    {{ $estimate->expires_at->format('Y-m-d H:i') }}
</p>

<hr>

<h2>Jūsų sprendimas</h2>

<p>
    Ar sutinkate su pateikta remonto sąmata?
</p>

<form action="{{ route('estimates.approve', $token) }}" method="POST">
    @csrf

    <button type="submit"
            onclick="return confirm('Ar tikrai norite patvirtinti sąmatą?')">
        Patvirtinti sąmatą
    </button>
</form>

<br>

<form action="{{ route('estimates.reject', $token) }}" method="POST">
    @csrf

    <button type="submit"
            onclick="return confirm('Ar tikrai norite atmesti sąmatą?')">
        Atmesti sąmatą
    </button>
</form>

</body>
</html>

