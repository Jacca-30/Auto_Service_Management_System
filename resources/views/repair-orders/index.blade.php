<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remonto užsakymai</title>
</head>
<body>

<h1>Remonto užsakymai</h1>

<p>Autoserviso gautos remonto užklausos.</p>

@if ($repairOrders->isEmpty())
    <p>Remonto užsakymų kol kas nėra.</p>
@else
    <table border="1" cellpadding="10">
        <thead>
        <tr>
            <th>Nr.</th>
            <th>Klientas</th>
            <th>Automobilis</th>
            <th>Valstybinis numeris</th>
            <th>Gedimo aprašymas</th>
            <th>Statusas</th>
            <th>Data</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($repairOrders as $order)
            <tr>
                <td>
                    <a href="{{ route('repair-orders.show', $order->id) }}">
                        #{{ $order->id }}
                    </a>
                </td>

                <td>
                    {{ $order->customerVehicle->customer->name }}
                </td>

                <td>
                    {{ $order->customerVehicle->vehicle->make }}
                    {{ $order->customerVehicle->vehicle->model }}
                </td>

                <td>
                    {{ $order->customerVehicle->license_plate }}
                </td>

                <td>
                    {{ $order->customer_complaint }}
                </td>

                <td>{{ $order->status }}</td>

                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $repairOrders->links() }}
@endif

</body>
</html>
