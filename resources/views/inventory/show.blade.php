
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventoriaus judėjimų istorija</title>
</head>
<body>

<h1>Prekės judėjimų istorija</h1>

<a href="{{ route('inventory.index') }}">Grįžti į inventorių</a>

<p>
    Dalis:
    {{ $item->name }}
</p>

<p>
    Tipas:
    {{ $item->type === 'part' ? 'Atsarginė dalis' : 'Medžiaga' }}
</p>

<p>Esamas likutis: {{ $item->quantity }} {{ $item->unit }}</p>

<h2>Judėjimai</h2>

@if ($movements->isEmpty())
    <p>Ši prekė dar neturi judėjimų istorijos.</p>
@else
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
        <tr>
            <th>Data</th>
            <th>Veiksmas</th>
            <th>Kiekis</th>
            <th>Remonto užsakymas</th>
            <th>Atliko</th>
            <th>Pastaba</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>

                <td>
                    @if ($movement->type === 'received')
                        Atsargos papildytos
                    @elseif ($movement->type === 'used')
                        Panaudota remontui
                    @else
                        Likučio koregavimas
                    @endif
                </td>

                <td>{{ $movement->quantity }} {{ $item->unit }}</td>

                <td>
                    {{ $movement->repair_order_id
                        ? '#' . $movement->repair_order_id
                        : '-' }}
                </td>

                <td>{{ $movement->performedBy->name ?? '-' }}</td>

                <td>{{ $movement->notes ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div>
        {{ $movements->links() }}
    </div>
@endif

</body>
</html>
