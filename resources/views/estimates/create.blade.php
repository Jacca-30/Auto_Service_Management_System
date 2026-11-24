
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sąmatos kūrimas</title>
</head>
<body>

<h1>Remonto sąmatos kūrimas</h1>

<p>
    <a href="{{ route('repair-orders.show', $repairOrder->id) }}">
        ← Grįžti į remonto užsakymą
    </a>
</p>

<h2>Užsakymas #{{ $repairOrder->id }}</h2>

<p>
    <strong>Klientas:</strong>
    {{ $repairOrder->customerVehicle->customer->name }}
</p>

<p>
    <strong>Automobilis:</strong>
    {{ $repairOrder->customerVehicle->vehicle->make }}
    {{ $repairOrder->customerVehicle->vehicle->model }}
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

<form action="{{ route('repair-orders.estimates.store', $repairOrder->id) }}" method="POST">
    @csrf

    <h2>Sąmatos eilutės</h2>

    <div id="estimate-items">

        <div class="estimate-item">
            <h3>1 eilutė</h3>

            <label>Inventoriaus prekė (nebūtina):</label><br>
            <select class="inventory-select" onchange="fillInventoryItem(this)">
                <option value="">-- Įvesti rankiniu būdu --</option>

                @foreach ($inventoryItems as $inventoryItem)
                    <option
                        value="{{ $inventoryItem->id }}"
                        data-name="{{ $inventoryItem->name }}"
                        data-type="{{ $inventoryItem->type }}"
                        data-oem="{{ $inventoryItem->oem_number }}"
                        data-ap="{{ $inventoryItem->ap_number }}"
                    >
                        {{ $inventoryItem->name }}
                        @if ($inventoryItem->oem_number)
                            (OEM: {{ $inventoryItem->oem_number }})
                        @endif
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="items[0][inventory_item_id]" value="">

            <br><br>

            <label>Tipas:</label><br>
            <select name="items[0][type]" required>
                <option value="labor">Darbas</option>
                <option value="part">Detalė</option>
                <option value="material">Medžiaga</option>
            </select>

            <br><br>

            <label>Aprašymas:</label><br>
            <input type="text" name="items[0][description]" required>

            <br><br>

            <label>OEM kodas:</label><br>
            <input type="text" name="items[0][oem_number]">

            <br><br>

            <label>Alternatyvus detalės kodas:</label><br>
            <input type="text" name="items[0][ap_number]">

            <br><br>

            <label>Kiekis:</label><br>
            <input type="number"
                   name="items[0][quantity]"
                   min="0.01"
                   step="0.01"
                   value="1"
                   required>

            <br><br>

            <label>Vieneto pardavimo kaina klientui (€):</label><br>
            <input type="number"
                   name="items[0][unit_price]"
                   min="0"
                   step="0.01"
                   required>

            <br><br>

            <hr>
        </div>

    </div>

    <button type="button" onclick="addItem()">
        + Pridėti eilutę
    </button>

    <br><br>

    <button type="submit">
        Išsaugoti sąmatą
    </button>
</form>

<script>
    let itemIndex = 1;

    function fillInventoryItem(select) {
        const item = select.closest('.estimate-item');
        const option = select.options[select.selectedIndex];

        const inventoryId = select.value;

        item.querySelector('input[name$="[inventory_item_id]"]').value = inventoryId;

        if (!inventoryId) {
            return;
        }

        item.querySelector('input[name$="[description]"]').value =
            option.dataset.name || '';

        item.querySelector('input[name$="[oem_number]"]').value =
            option.dataset.oem || '';

        item.querySelector('input[name$="[ap_number]"]').value =
            option.dataset.ap || '';

        item.querySelector('select[name$="[type]"]').value =
            option.dataset.type || 'part';
    }

    function addItem() {
        const container = document.getElementById('estimate-items');

        const item = document.createElement('div');
        item.classList.add('estimate-item');

        item.innerHTML = `
            <h3>${itemIndex + 1} eilutė</h3>

            <label>Inventoriaus prekė (nebūtina):</label><br>
            <select class="inventory-select" onchange="fillInventoryItem(this)">
                <option value="">-- Įvesti rankiniu būdu --</option>

                @foreach ($inventoryItems as $inventoryItem)
        <option
            value="{{ $inventoryItem->id }}"
                        data-name="{{ e($inventoryItem->name) }}"
                        data-type="{{ $inventoryItem->type }}"
                        data-oem="{{ e($inventoryItem->oem_number ?? '') }}"
                        data-ap="{{ e($inventoryItem->ap_number ?? '') }}"
                    >
                        {{ $inventoryItem->name }}
        @if ($inventoryItem->oem_number)
        (OEM: {{ $inventoryItem->oem_number }})
                        @endif
        </option>
@endforeach
        </select>

        <input type="hidden" name="items[${itemIndex}][inventory_item_id]" value="">

            <br><br>

            <label>Tipas:</label><br>
            <select name="items[${itemIndex}][type]" required>
                <option value="labor">Darbas</option>
                <option value="part">Detalė</option>
                <option value="material">Medžiaga</option>
            </select>

            <br><br>

            <label>Aprašymas:</label><br>
            <input type="text" name="items[${itemIndex}][description]" required>

            <br><br>

            <label>OEM kodas:</label><br>
            <input type="text" name="items[${itemIndex}][oem_number]">

            <br><br>

            <label>Alternatyvus detalės kodas:</label><br>
            <input type="text" name="items[${itemIndex}][ap_number]">

            <br><br>

            <label>Kiekis:</label><br>
            <input type="number"
                   name="items[${itemIndex}][quantity]"
                   min="0.01"
                   step="0.01"
                   value="1"
                   required>

            <br><br>

            <label>Vieneto pardavimo kaina klientui (€):</label><br>
            <input type="number"
                   name="items[${itemIndex}][unit_price]"
                   min="0"
                   step="0.01"
                   required>

            <br><br>

            <hr>
        `;

        container.appendChild(item);
        itemIndex++;
    }
</script>

</body>
</html>
