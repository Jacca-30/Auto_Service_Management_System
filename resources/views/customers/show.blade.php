<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kliento informacija</title>
</head>
<body>

<h1>Kliento informacija</h1>

<p>
    <a href="{{ route('customers.index') }}">Grįžti į klientų sąrašą</a>
</p>

<h2>Kontaktiniai duomenys</h2>

<p><strong>Vardas, pavardė:</strong> {{ $customer->name }}</p>
<p><strong>El. paštas:</strong> {{ $customer->email }}</p>
<p><strong>Telefonas:</strong> {{ $customer->phone }}</p>

<hr>

<h2>Kliento automobiliai</h2>

<p>
    <a href="{{ route('customers.vehicles.create', $customer->id) }}">
        + Pridėti automobilį
    </a>
</p>

@if ($customer->customerVehicles->isEmpty())
    <p>Šis klientas dar neturi automobilių.</p>
@else
    @foreach ($customer->customerVehicles as $customerVehicle)
        <div style="border: 1px solid #ccc; padding: 12px; margin-bottom: 12px;">

            <h3>
                {{ $customerVehicle->vehicle->make }}
                {{ $customerVehicle->vehicle->model }}
            </h3>

            <p><strong>Pagaminimo metai:</strong>
                {{ $customerVehicle->vehicle->year ?? 'Nenurodyta' }}
            </p>

            <p><strong>Kuro tipas:</strong>
                {{ $customerVehicle->vehicle->fuel_type ?? 'Nenurodyta' }}
            </p>

            <p><strong>Variklio kodas:</strong>
                {{ $customerVehicle->vehicle->engine_code ?? 'Nenurodyta' }}
            </p>

            <p><strong>Darbinis tūris:</strong>
                {{ $customerVehicle->vehicle->engine_displacement_cm3 ?? 'Nenurodyta' }}
                @if ($customerVehicle->vehicle->engine_displacement_cm3)
                    cm³
                @endif
            </p>

            <p><strong>Galia:</strong>
                {{ $customerVehicle->vehicle->engine_power_kw ?? 'Nenurodyta' }}
                @if ($customerVehicle->vehicle->engine_power_kw)
                    kW
                @endif
            </p>

            <p><strong>Pavarų dėžė:</strong>
                {{ $customerVehicle->vehicle->transmission_type ?? 'Nenurodyta' }}
            </p>

            <p><strong>VIN:</strong>
                {{ $customerVehicle->vin ?? 'Nenurodyta' }}
            </p>

            <p><strong>Valstybinis numeris:</strong>
                {{ $customerVehicle->license_plate ?? 'Nenurodyta' }}
            </p>

            <p><strong>Registracijos liudijimo numeris:</strong>
                {{ $customerVehicle->reg_cert_number ?? 'Nenurodyta' }}
            </p>

        </div>
    @endforeach
@endif

</body>
</html>
