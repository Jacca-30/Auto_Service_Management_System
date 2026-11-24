<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąmatos atsakymas</title>
</head>
<body>

<h1>Sąmatos atsakymas</h1>

@if ($status === 'approved')
    <h2>Sąmata patvirtinta!</h2>
    <p>Ačiū. Jūsų sąmata sėkmingai patvirtinta.</p>
    <p>Autoservisas gali pradėti remonto darbus.</p>

@elseif ($status === 'rejected')
    <h2>Sąmata atmesta</h2>
    <p>Informavome autoservisą apie jūsų sprendimą.</p>

@else
    <h2>Nuoroda nebegalioja</h2>
    <p>Sąmatos nuoroda nebegalioja arba sąmata jau buvo apdorota.</p>
    <p>Jeigu reikia, susisiekite su autoservisu.</p>
@endif

</body>
</html>
