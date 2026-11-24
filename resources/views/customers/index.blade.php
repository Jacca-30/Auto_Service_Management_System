
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientai</title>
</head>
<body>
    <h1>Klientai</h1>

    <p>
        Autoservisas: {{ auth()->user()->tenant->name }}
    </p>

    <th>
        Veiksmai
    </th>

    <p>
        <a href="{{ route('dashboard') }}">Grįžti į skydelį</a>
    </p>

    <p>
        <a href="{{ route('customers.create') }}">+ Pridėti klientą</a>
    </p>


    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <hr>

    @if ($customers->isEmpty())
        <p>Klientų dar nėra.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
            <tr>
                <th>Vardas, pavardė</th>
                <th>El. paštas</th>
                <th>Telefono numeris</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>
                        <a href="{{ route('customers.show', $customer->id) }}">
                            {{ $customer->name }}
                        </a>
                    </td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <a href="{{ route('customers.vehicles.create', $customer->id) }}">
                            + Pridėti automobilį
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $customers->links() }}
    @endif
</body>
</html>
