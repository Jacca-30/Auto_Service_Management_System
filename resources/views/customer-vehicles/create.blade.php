<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridėti automobilį</title>
</head>
<body>

<h1>Pridėti automobilį</h1>

<p>Klientas: {{ $customer->name }}</p>

<p>
    <a href="{{ route('customers.index') }}">Grįžti į klientų sąrašą</a>
</p>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('customers.vehicles.store', $customer->id) }}">
    @csrf

    <h3>Automobilio duomenys</h3>

    <label>Markė *</label><br>
    <input type="text" name="make" value="{{ old('make') }}" required><br><br>

    <label>Modelis *</label><br>
    <input type="text" name="model" value="{{ old('model') }}" required><br><br>

    <label>Pagaminimo metai</label><br>
    <input type="number" name="year" value="{{ old('year') }}"><br><br>

    <label>Kuro tipas</label><br>
    <input type="text" name="fuel_type" value="{{ old('fuel_type') }}"><br><br>

    <label>Variklio tipas</label><br>
    <input type="text" name="engine_type" value="{{ old('engine_type') }}"><br><br>

    <label>Variklio darbinis tūris (cm³)</label><br>
    <input type="number" name="engine_displacement_cm3" value="{{ old('engine_displacement_cm3') }}"><br><br>

    <label>Variklio galia (kW)</label><br>
    <input type="number" name="engine_power_kw" value="{{ old('engine_power_kw') }}"><br><br>

    <label>Pavarų dėžė</label><br>
    <input type="text" name="transmission_type" value="{{ old('transmission_type') }}"><br><br>

    <h3>Registracijos duomenys</h3>

    <label>VIN kodas</label><br>
    <input type="text" name="vin" maxlength="17" value="{{ old('vin') }}"><br><br>

    <label>Valstybinis numeris</label><br>
    <input type="text" name="license_plate" value="{{ old('license_plate') }}"><br><br>

    <label>Registracijos liudijimo numeris</label><br>
    <input type="text" name="reg_cert_number" value="{{ old('reg_cert_number') }}"><br><br>

    <button type="submit">Išsaugoti automobilį</button>
</form>
</body>
</html>
