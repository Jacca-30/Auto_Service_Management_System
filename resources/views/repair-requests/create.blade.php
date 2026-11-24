
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remonto užklausa – {{ $tenant->name }}</title>
</head>
<body>
<h1>Remonto užklausa</h1>
<h2>{{ $tenant->name }}</h2>

<p>Užpildykite formą ir susisieksime su jumis.</p>

@if (session('success'))
    <div>
        <strong>{{ session('success') }}</strong>
    </div>
@endif

@if ($errors->any())
    <div>
        <strong>Patikrinkite įvestus duomenis:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('repair-requests.store', $tenant->slug) }}" method="POST">
    @csrf

    <h3>Kliento duomenys</h3>

    <label for="name">Vardas ir pavardė *</label><br>
    <input type="text" id="name" name="name"
           value="{{ old('name') }}" required><br><br>

    <label for="email">El. paštas *</label><br>
    <input type="email" id="email" name="email"
           value="{{ old('email') }}" required><br><br>

    <label for="phone">Telefono numeris *</label><br>
    <input type="tel" id="phone" name="phone"
           value="{{ old('phone') }}" required><br><br>

    <h3>Automobilio duomenys</h3>

    <label for="make">Markė *</label><br>
    <input type="text" id="make" name="make"
           value="{{ old('make') }}" required><br><br>

    <label for="model">Modelis *</label><br>
    <input type="text" id="model" name="model"
           value="{{ old('model') }}" required><br><br>

    <label for="year">Pagaminimo metai *</label><br>
    <input type="number" id="year" name="year"
           value="{{ old('year') }}" required><br><br>

    <label for="fuel_type">Kuro tipas *</label><br>
    <input type="text" id="fuel_type" name="fuel_type"
           value="{{ old('fuel_type') }}" required><br><br>

    <label for="engine_code">Variklio kodas</label><br>
    <input type="text" id="engine_code" name="engine_code"
           value="{{ old('engine_code') }}"><br><br>

    <label for="engine_displacement_cm3">Variklio darbinis tūris (cm³)</label><br>
    <input type="number" id="engine_displacement_cm3"
           name="engine_displacement_cm3"
           value="{{ old('engine_displacement_cm3') }}"><br><br>

    <label for="engine_power_kw">Variklio galia (kW)</label><br>
    <input type="number" id="engine_power_kw" name="engine_power_kw"
           value="{{ old('engine_power_kw') }}"><br><br>

    <label for="transmission_type">Pavarų dėžė *</label><br>
    <input type="text" id="transmission_type" name="transmission_type"
           value="{{ old('transmission_type') }}" required><br><br>

    <label for="vin">VIN kodas</label><br>
    <input type="text" id="vin" name="vin"
           value="{{ old('vin') }}" maxlength="17"><br><br>

    <label for="license_plate">Valstybinis numeris *</label><br>
    <input type="text" id="license_plate" name="license_plate"
           value="{{ old('license_plate') }}" required><br><br>

    <label for="reg_cert_number">Registracijos liudijimo numeris</label><br>
    <input type="text" id="reg_cert_number" name="reg_cert_number"
           value="{{ old('reg_cert_number') }}"><br><br>

    <h3>Informacija apie gedimą</h3>

    <label for="customer_complaint">Aprašykite automobilio problemą *</label><br>
    <textarea id="customer_complaint"
              name="customer_complaint"
              rows="5" required>{{ old('customer_complaint') }}</textarea><br><br>

    <button type="submit">Pateikti užklausą</button>
</form>
</body>
</html>
