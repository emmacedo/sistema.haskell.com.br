<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova Mensagem de Contato - Notificação Admin</title>
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
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .badge {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 0 0 8px 8px;
        }
        .section {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .section-title {
            font-weight: bold;
            color: #343a40;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3d5a47;
        }
        .field {
            margin-bottom: 8px;
        }
        .field-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }
        .message-box {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background: #3d5a47;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <span class="badge">NOTIFICAÇÃO ADMIN</span>
        <h1 style="margin: 10px 0 0 0;">Nova Mensagem de Contato</h1>
    </div>

    <div class="content">
        <p>Olá <strong>{{ $admin->name }}</strong>,</p>
        <p>Uma nova mensagem de contato foi recebida através do sistema.</p>

        <div class="section">
            <div class="section-title">Dados do Remetente</div>

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
                {{ $contactMessage->sender_phone }}
            </div>

            <div class="field">
                <div class="field-label">Localização</div>
                {{ $contactMessage->sender_city }} - {{ $contactMessage->sender_state }}
            </div>

            @if($contactMessage->product)
            <div class="field">
                <div class="field-label">Produto de Interesse</div>
                {{ $contactMessage->product->name }}
            </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Destinatário</div>

            <div class="field">
                <div class="field-label">Distribuidor</div>
                {{ $distributor->trade_name }} ({{ $distributor->company_name }})
            </div>

            @if($seller)
            <div class="field">
                <div class="field-label">Vendedor</div>
                {{ $seller->name }} - {{ $seller->email }}
            </div>
            @endif
        </div>

        <div class="message-box">
            <div class="field-label">Mensagem</div>
            <p style="margin: 10px 0 0 0; white-space: pre-wrap;">{{ $contactMessage->message }}</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/admin/contact-messages/' . $contactMessage->id) }}" class="btn">
                Ver no Painel Admin
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Sistema de Distribuidores - Haskell Cosméticos</p>
        <p>Mensagem recebida em {{ $contactMessage->created_at->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
