<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Não Aprovado</title>
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
            background-color: #dc3545;
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
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 12px;
        }
        .reason-box {
            background-color: white;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sobre seu Cadastro</h1>
    </div>

    <div class="content">
        <p>Olá, <strong>{{ $distributor->trade_name }}</strong>!</p>

        <p>Agradecemos seu interesse em fazer parte do nosso sistema de distribuidores.</p>

        <p>Infelizmente, após análise, seu cadastro não foi aprovado neste momento.</p>

        @if($reason)
            <div class="reason-box">
                <p><strong>Motivo:</strong></p>
                <p>{{ $reason }}</p>
            </div>
        @endif

        <p>Se você acredita que houve algum equívoco ou deseja mais informações, por favor entre em contato conosco.</p>

        <p>Você pode tentar realizar um novo cadastro corrigindo as informações necessárias.</p>

        <p>Atenciosamente,<br>
        <strong>Equipe Sistema de Distribuidores</strong></p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} Sistema de Distribuidores. Todos os direitos reservados.</p>
    </div>
</body>
</html>
