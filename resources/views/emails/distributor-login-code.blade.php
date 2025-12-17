<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de Acesso</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #0d8784;">Código de Acesso - Haskell Cosméticos</h2>

        <p>Olá, {{ $distributor->trade_name }}!</p>

        <p>Você solicitou acesso à sua área do distribuidor. Use o código abaixo para fazer login:</p>

        <div style="background-color: #f4f4f4; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px;">
            <h1 style="color: #0d8784; font-size: 36px; margin: 0; letter-spacing: 5px;">{{ $code }}</h1>
        </div>

        <p>Este código é válido por tempo limitado e pode ser usado apenas uma vez.</p>

        <p style="color: #999; font-size: 12px; margin-top: 30px;">
            Se você não solicitou este código, ignore este e-mail.
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="color: #999; font-size: 12px; text-align: center;">
            Haskell Cosméticos - Sistema de Distribuidores
        </p>
    </div>
</body>
</html>
