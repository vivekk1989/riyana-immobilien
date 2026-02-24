<!DOCTYPE html>
<html>

<head>
    <title>New Contact Request</title>
</head>

<body style="font-family: sans-serif;">
    <h2>Neuer Kontakt von Webseite</h2>

    @if(isset($unit) && $unit)
        <div style="background-color: #eeffff; padding: 10px; border-left: 4px solid #00aaaa; margin-bottom: 20px;">
            <h3>Betreffendes Objekt:</h3>
            <p><strong>Adresse:</strong> {{ $unit->property->address }}</p>
            <p><strong>Einheit:</strong> {{ $unit->unit_number }}</p>
        </div>
    @endif

    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Telefon:</strong> {{ $data['phone'] ?? 'Nicht angegeben' }}</p>

    <p><strong>Nachricht:</strong></p>
    <div style="background: #f3f4f6; padding: 15px; border-radius: 5px;">
        {{ $data['message'] }}
    </div>
</body>

</html>