<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Aprovado</title>
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
            background-color: #198754;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .success-icon {
            text-align: center;
            font-size: 48px;
            color: #198754;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 12px;
        }
        .info-box {
            background-color: white;
            border-left: 4px solid #198754;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cadastro Aprovado!</h1>
    </div>

    <div class="content">
        <div class="success-icon">✓</div>

        <p>Parabéns, <strong>{{ $distributor->trade_name }}</strong>!</p>

        <p>Seu cadastro foi aprovado e agora você está oficialmente listado em nosso sistema de distribuidores!</p>

        <div class="info-box">
            <p><strong>O que isso significa?</strong></p>
            <ul>
                <li>Clientes poderão encontrar sua empresa ao buscar distribuidores na região</li>
                <li>Suas informações de contato estarão disponíveis para consulta</li>
                <li>Você receberá leads qualificados de clientes interessados</li>
            </ul>
        </div>

        <p><strong>Suas informações cadastradas:</strong></p>
        <ul>
            <li><strong>Razão Social:</strong> {{ $distributor->company_name }}</li>
            <li><strong>Nome Fantasia:</strong> {{ $distributor->trade_name }}</li>
            <li><strong>CNPJ:</strong> {{ $distributor->cnpj }}</li>
            <li><strong>Email:</strong> {{ $distributor->email }}</li>
            <li><strong>Telefone:</strong> {{ $distributor->phone }}</li>
            @if($distributor->whatsapp)
                <li><strong>WhatsApp:</strong> {{ $distributor->whatsapp }}</li>
            @endif
        </ul>

        <p><strong>Cidades atendidas:</strong></p>
        <p>{{ $distributor->cities->pluck('name')->join(', ') }}</p>

        <p>Se você precisar atualizar qualquer informação, entre em contato conosco.</p>

        <p>Atenciosamente,<br>
        <strong>Equipe Sistema de Distribuidores</strong></p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} Sistema de Distribuidores. Todos os direitos reservados.</p>
    </div>
</body>
</html>
