
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridėti inventoriaus prekę</title>
</head>
<body>

<h1>Naujos inventoriaus prekės pridėjimas</h1>

<a href="{{ route('inventory.index') }}">Grįžti į inventorių</a>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('inventory.store') }}" method="POST">
    @csrf

    <p>
        <label>Prekės tipas:</label><br>
        <select name="type" required>
            <option value="">Pasirinkite</option>
            <option value="part" {{ old('type') === 'part' ? 'selected' : '' }}>
                Atsarginė dalis
            </option>
            <option value="material" {{ old('type') === 'material' ? 'selected' : '' }}>
                Medžiaga
            </option>
        </select>
    </p>

    <p>
        <label>Pavadinimas:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </p>

    <p>
        <label>OEM numeris:</label><br>
        <input type="text" name="oem_number" value="{{ old('oem_number') }}">
    </p>

    <p>
        <label>AP numeris:</label><br>
        <input type="text" name="ap_number" value="{{ old('ap_number') }}">
    </p>

    <p>
        <label>Matavimo vienetas (vnt., l, kg ir pan.):</label><br>
        <input type="text" name="unit" value="{{ old('unit', 'vnt.') }}" required>
    </p>

    <p>
        <label>Minimalus kiekis:</label><br>
        <input type="number" name="minimum_quantity"
               value="{{ old('minimum_quantity', 0) }}"
               min="0" step="0.01" required>
    </p>

    <p>
        <label>Pirkimo kaina (€):</label><br>
        <input type="number" name="purchase_price"
               value="{{ old('purchase_price', 0) }}"
               min="0" step="0.01" required>
    </p>

    <p>
        <button type="submit">Išsaugoti prekę</button>
    </p>
</form>

</body>
</html>
