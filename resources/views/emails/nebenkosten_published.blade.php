<!DOCTYPE html>
<html>

<head>
    <title>Nebenkostenabrechnung {{ $year }}</title>
</head>

<body style="font-family: sans-serif; padding: 20px;">
    <h2>Hallo,</h2>

    <p>Die Nebenkostenabrechnung für das Jahr {{ $year }} für Ihre Einheit ({{ $unit->property->address }} - Einheit
        {{ $unit->unit_number }}) liegt nun vor.</p>

    <p>Sie finden die detaillierte Abrechnung im Anhang dieser E-Mail oder können sie jederzeit im Mieterportal
        herunterladen.</p>

    <p>Bei Rückfragen stehen wir Ihnen gerne zur Verfügung.</p>

    <p>Mit freundlichen Grüßen,<br>
        Ihr Riyana Immobilien Team</p>
</body>

</html>