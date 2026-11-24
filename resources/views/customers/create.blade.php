<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naujas klientas</title>
</head>
<body>

<h1>Naujo kliento registravimas</h1>

<p>
    Autoservisas: {{ auth()->user()->tenant->name }}
</p>

<a href="{{ route('customers.index') }}">Grįžti į klientų sąrašą</a>

<hr>

<form method="POST" action="{{ route('customers.store') }}">
    @csrf

    <div>
        <label for="name">Vardas ir pavardė:</label><br>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
        >
        @error('name')
        <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="email">El. paštas:</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
        >
        @error('email')
        <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="phone">Telefono numeris:</label><br>
        <input
            type="text"
            id="phone"
            name="phone"
            value="{{ old('phone') }}"
            required
        >
        @error('phone')
        <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button type="submit">Išsaugoti klientą</button>
</form>

</body>
</html>
