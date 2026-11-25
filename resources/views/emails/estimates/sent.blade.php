
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automobilio remonto sąmata</title>
</head>
<body style="margin: 0; padding: 30px 15px; background-color: #f1f5f9; font-family: Arial, sans-serif; color: #334155;">

<div style="max-width: 700px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden;">

    <div style="background-color: #1e293b; padding: 28px 30px; color: #ffffff;">
        <h1 style="margin: 0; font-size: 24px;">
            Automobilio remonto sąmata
        </h1>
        <p style="margin: 8px 0 0; color: #cbd5e1; font-size: 14px;">
            Jūsų autoserviso parengtas pasiūlymas
        </p>
    </div>

    <div style="padding: 30px;">

        <p style="font-size: 16px;">
            Sveiki, <strong>{{ $estimate->repairOrder->customerVehicle->customer->name }}</strong>!
        </p>

        <p style="line-height: 1.6;">
            Autoservisas parengė Jūsų automobilio remonto sąmatą.
            Žemiau pateikiame remonto darbų ir reikalingų dalių informaciją.
        </p>

        <div style="margin: 25px 0; padding: 18px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
            <h2 style="margin: 0 0 15px; font-size: 18px; color: #1e293b;">
                Sąmatos informacija
            </h2>

            <p style="margin: 8px 0;">
                <strong>Sąmatos numeris:</strong>
                #{{ $estimate->id }}
            </p>

            <p style="margin: 8px 0;">
                <strong>Automobilis:</strong>
                {{ $estimate->repairOrder->customerVehicle->vehicle->make }}
                {{ $estimate->repairOrder->customerVehicle->vehicle->model }}
            </p>
        </div>

        <h2 style="font-size: 18px; color: #1e293b; margin-bottom: 12px;">
            Remonto darbai ir detalės
        </h2>

        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
            <tr style="background-color: #e2e8f0; text-align: left;">
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1;">Tipas</th>
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1;">Aprašymas</th>
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1;">Kiekis</th>
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1;">Vieneto kaina</th>
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1;">Suma</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($estimate->items as $item)
                <tr>
                    <td style="padding: 10px 8px; border: 1px solid #e2e8f0;">
                        {{ $item->type }}
                    </td>
                    <td style="padding: 10px 8px; border: 1px solid #e2e8f0;">
                        {{ $item->description }}
                    </td>
                    <td style="padding: 10px 8px; border: 1px solid #e2e8f0;">
                        {{ $item->quantity }}
                    </td>
                    <td style="padding: 10px 8px; border: 1px solid #e2e8f0;">
                        {{ number_format((float) $item->unit_price, 2) }} €
                    </td>
                    <td style="padding: 10px 8px; border: 1px solid #e2e8f0;">
                        {{ number_format((float) $item->total_price, 2) }} €
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="margin-top: 25px; padding: 18px; background-color: #eff6ff; border-radius: 8px; text-align: right;">
            <span style="font-size: 14px; color: #475569;">
                Bendra remonto sąmata:
            </span>
            <div style="font-size: 26px; font-weight: bold; color: #1d4ed8; margin-top: 5px;">
                {{ number_format((float) $estimate->total_amount, 2) }} €
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <p style="line-height: 1.6;">
                Norėdami peržiūrėti sąmatą ir ją patvirtinti arba atmesti,
                paspauskite žemiau esantį mygtuką:
            </p>

            <a href="{{ $approvalUrl }}"
               style="display: inline-block; padding: 14px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold;">
                Peržiūrėti sąmatą
            </a>
        </div>

        <p style="margin-top: 35px; line-height: 1.6;">
            Pagarbiai,<br>
            <strong>Jūsų autoservisas</strong>
        </p>
    </div>

    <div style="padding: 16px 30px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 12px;">
        Šis laiškas sugeneruotas automatiškai. Prašome neatsakyti į jį.
    </div>

</div>

</body>
</html>
