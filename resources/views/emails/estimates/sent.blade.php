
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Automobilio remonto sąmata</title>
</head>
<body>

<h1>Jūsų automobilio remonto sąmata</h1>

<p>Sveiki, {{ $estimate->repairOrder->customerVehicle->customer->name }}!</p>

<p>
    Autoservisas parengė Jūsų automobilio remonto sąmatą.
</p>

<h2>Sąmatos informacija</h2>

<p>
    <strong>Sąmatos numeris:</strong>
    {{ $estimate->id }}
</p>

<p>
    <strong>Automobilis:</strong>
    {{ $estimate->repairOrder->customerVehicle->vehicle->make }}
    {{ $estimate->repairOrder->customerVehicle->vehicle->model }}
</p>

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
    Norėdami patvirtinti arba atmesti sąmatą, paspauskite šią nuorodą:
</p>

<p>
    <a href="{{ $approvalUrl }}">
        Peržiūrėti ir patvirtinti arba atmesti sąmatą
    </a>
</p>

<p>
    Pagarbiai,<br>
    Jūsų autoservisas
</p>

</body>
</html>
