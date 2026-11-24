
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventorius</title>
</head>
<body>

<h1>Autoserviso inventorius</h1>

<a href="{{ route('dashboard') }}">Grįžti į pagrindinį puslapį</a>

<p>
    <a href="{{ route('inventory.create') }}">+ Pridėti naują prekę</a>
</p>

@if (session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

@if (session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
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

<h2>Inventoriaus prekės</h2>

@if ($inventoryItems->isEmpty())
    <p>Inventoriuje prekių dar nėra.</p>
@else
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
        <tr>
            <th>Pavadinimas</th>
            <th>Tipas</th>
            <th>OEM numeris</th>
            <th>AP numeris</th>
            <th>Likutis</th>
            <th>Minimalus kiekis</th>
            <th>Matavimo vienetas</th>
            <th>Pirkimo kaina</th>
            <th>Būsena</th>
            <th>Veiksmai</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($inventoryItems as $item)
            <tr>
                <td>
                    {{ $item->name }}
                    <br>
                    <a href="{{ route('inventory.show', $item->id) }}">
                        Peržiūrėti judėjimų istoriją
                    </a>
                </td>

                <td>
                    {{ $item->type === 'part' ? 'Atsarginė dalis' : 'Medžiaga' }}
                </td>

                <td>{{ $item->oem_number ?? '-' }}</td>
                <td>{{ $item->ap_number ?? '-' }}</td>

                <td>{{ $item->quantity }}</td>
                <td>{{ $item->minimum_quantity }}</td>
                <td>{{ $item->unit }}</td>

                <td>{{ number_format((float) $item->purchase_price, 2, ',', ' ') }} €</td>

                <td>
                    @if ((float) $item->quantity <= (float) $item->minimum_quantity)
                        <span style="color: red;">Reikia papildyti</span>
                    @else
                        <span style="color: green;">Pakanka</span>
                    @endif
                </td>

                <td>
                    <h4>Papildyti atsargas</h4>

                    <form action="{{ route('inventory.receive', $item->id) }}" method="POST">
                        @csrf

                        <input
                            type="number"
                            name="quantity"
                            min="0.01"
                            step="0.01"
                            placeholder="Kiekis"
                            required
                        >

                        <input
                            type="text"
                            name="notes"
                            placeholder="Pastaba (nebūtina)"
                        >

                        <button type="submit">Papildyti</button>
                    </form>

                    <hr>

                    <h4>Panaudoti remontui</h4>

                    @if ($repairOrders->isNotEmpty())
                        <form action="{{ route('inventory.consume', $item->id) }}" method="POST">
                            @csrf

                            <select name="repair_order_id" required>
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
                            >

                            <input
                                type="text"
                                name="notes"
                                placeholder="Pastaba (nebūtina)"
                            >

                            <button type="submit">Registruoti panaudojimą</button>
                        </form>
                    @else
                        <p>Nėra vykdomų remonto užsakymų.</p>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $inventoryItems->links() }}
    </div>
@endif

</body>
</html>
