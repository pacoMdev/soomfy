<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a {{ config('app.name') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
            text-align: center;
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
        <h2>¡Bienvenido!</h2>
        <p>Gracias por unirte a <strong>Soomfy</strong>. Estamos encantados de tenerte con nosotros.</p>
        <p>Ahora puedes explorar, comprar y vender productos de manera fácil y segura.</p>

        <p style="text-align: center;">
            <a href="" class="button">Ir a la aplicación</a>
        </p>

        <p>Si tienes alguna pregunta, no dudes en contactarnos. ¡Disfruta de la experiencia!</p>
        <p>Atentamente, <br> El equipo de Soomfy</p>

        <p class="footer">Si no creaste esta cuenta, ignora este mensaje.</p>
    </div>
</body>
</html>