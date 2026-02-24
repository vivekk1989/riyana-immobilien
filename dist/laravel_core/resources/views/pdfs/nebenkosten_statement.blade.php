<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Nebenkostenabrechnung {{ $year }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" class="logo" alt="Logo">
        <h1>Nebenkostenabrechnung {{ $year }}</h1>
        <h3>{{ $unit->property->address }} - Unit {{ $unit->unit_number }}</h3>
    </div>

    <div class="details">
        <p><strong>Abrechnungszeitraum:</strong> 01.01.{{ $year }} - 31.12.{{ $year }}</p>
        <p><strong>Größe:</strong> {{ $unit->size }} m²</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kategorie</th>
                <th class="text-right">Gesamtkosten (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breakdown as $item)
                <tr>
                    <td>{{ $item['category'] }}</td>
                    <td class="text-right">{{ number_format($item['total'], 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="total">Gesamtsumme</td>
                <td class="total">{{ number_format($totalCost, 2, ',', '.') }} €</td>
            </tr>
        </tfoot>
    </table>

    <p>Bitte überweisen Sie den Betrag innerhalb von 14 Tagen.</p>
</body>

</html>