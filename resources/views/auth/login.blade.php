<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prisijungimas – Autoservisas</title>
</head>
<body>

<h1>Prisijungimas</h1>

@if ($errors->any())
    <div style="color: red;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div>
        <label for="email">El. paštas</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
        >
    </div>

    <div>
        <label for="password">Slaptažodis</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >
    </div>

    <div>
        <label>
            <input type="checkbox" name="remember" value="1">
            Prisiminti mane
        </label>
    </div>

    <button type="submit">Prisijungti</button>
</form>

</body>
</html>
