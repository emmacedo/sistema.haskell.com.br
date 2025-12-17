<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova Mensagem de Contato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #3d5a47;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f5e6d3;
            padding: 25px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            background: white;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 4px solid #3d5a47;
        }
        .field-label {
            font-weight: bold;
            color: #3d5a47;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .message-box {
            background: white;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
            border-left: 4px solid #3d5a47;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Nova Mensagem de Contato</h1>
    </div>

    <div class="content">
        <p>Olá <strong>{{ $seller->name }}</strong>,</p>
        <p>Você recebeu uma nova mensagem de contato através do sistema de distribuidores.</p>

        <h3 style="color: #3d5a47; margin-top: 20px;">Dados do Contato:</h3>

        <div class="field">
            <div class="field-label">Nome</div>
            {{ $contactMessage->sender_name }}
        </div>

        <div class="field">
            <div class="field-label">E-mail</div>
            <a href="mailto:{{ $contactMessage->sender_email }}">{{ $contactMessage->sender_email }}</a>
        </div>

        <div class="field">
            <div class="field-label">Telefone</div>
            <a href="tel:{{ $contactMessage->sender_phone }}">{{ $contactMessage->sender_phone }}</a>
        </div>

        <div class="field">
            <div class="field-label">Cidade / Estado</div>
            {{ $contactMessage->sender_city }} - {{ $contactMessage->sender_state }}
        </div>

        @if($contactMessage->product)
        <div class="field">
            <div class="field-label">Produto de Interesse</div>
            {{ $contactMessage->product->name }}
        </div>
        @endif

        <div class="message-box">
            <div class="field-label">Mensagem</div>
            <p style="margin: 10px 0 0 0; white-space: pre-wrap;">{{ $contactMessage->message }}</p>
        </div>

        <p style="margin-top: 20px;">
            <strong>Dica:</strong> Você pode responder diretamente a este e-mail para entrar em contato com {{ $contactMessage->sender_name }}.
        </p>
    </div>

    <div class="footer">
        <p>Esta mensagem foi enviada pelo Sistema de Distribuidores - Haskell Cosméticos</p>
        <p>{{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
