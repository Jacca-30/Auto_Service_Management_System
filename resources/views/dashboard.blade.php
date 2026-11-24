
<h1>Sveiki, {{ auth()->user()->name }}!</h1>

<p>Autoservisas: {{ auth()->user()->tenant->name }}</p>

<p>Rolė: {{ auth()->user()->role }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Atsijungti</button>
</form>

