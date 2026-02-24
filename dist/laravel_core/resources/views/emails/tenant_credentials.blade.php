<!DOCTYPE html>
<html>

<head>
    <title>Your Account Credentials</title>
</head>

<body style="font-family: sans-serif; padding: 20px;">
    <h2>{{ __('Welcome to Riyana Immobilien!') }}</h2>

    <p>{{ __('An account has been created for you. Please use the following credentials to log in:') }}</p>

    <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}<br>
        <strong>{{ __('Temporary Password:') }}</strong> {{ $password }}
    </p>

    <p>{{ __('You will be required to change your password upon your first login.') }}</p>

    <p>
        <a href="{{ route('login') }}"
            style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">{{ __('Login Now') }}</a>
    </p>

    <p>{{ __('Best regards,') }}<br>
        {{ __('Riyana Immobilien Team') }}</p>
</body>

</html>