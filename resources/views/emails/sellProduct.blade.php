<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Venta</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 600px; background: #fff; padding: 20px; margin: auto; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
        
        <h2 style="color: #333;">¡Has vendido tu producto!</h2>

        <p>Hola <strong>{{ $userSeller -> name }}</strong>,</p>
        <p>Tu producto <strong>{{ $product -> name }}</strong> ha sido vendido a <strong>{{ $userBuyer -> name }} {{ $userBuyer -> suername1 }}</strong>.</p>

        <h3>Detalles de la venta:</h3>
        <ul>
            <li><strong>Producto:</strong> {{ $product -> name }}</li>
            <li><strong>Precio:</strong> {{ $finalPrice }} €</li>
            <li><strong>Comprador:</strong> {{ $userBuyer -> name }} ({{ $userBuyer -> email }})</li>
            <li><strong>Fecha:</strong> {{ $saleDate }}</li>
        </ul>

        <p>Ponte en contacto con el comprador para coordinar la entrega.</p>

        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ $url }}" style="background: #28a745; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Ver Producto</a>
        </p>

        <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            Gracias por usar nuestra plataforma. 🎉
        </p>
    </div>

</body>
</html>