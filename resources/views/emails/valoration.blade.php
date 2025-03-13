<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valora tu compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hola {{ $user->name }},</h2>
        <p>Gracias por completar tu compra del producto <strong>{{ $product->title }}</strong>.</p>
        <p>Queremos saber tu opinión sobre la transacción y el vendedor <strong>{{ $seller->name }}</strong>.</p>
        <p>Tu valoración nos ayuda a mejorar la comunidad y la experiencia de compra.</p>

        <p style="text-align: center;">
            <a href="{{ $ratingUrl }}" class="button">Valorar ahora</a>
        </p>

        <p>¡Gracias por tu tiempo!</p>
        <p>Atentamente, <br> El equipo de {{ config('app.name') }}</p>

        <p class="footer">Si no realizaste esta compra, ignora este mensaje.</p>
    </div>
</body>
</html>