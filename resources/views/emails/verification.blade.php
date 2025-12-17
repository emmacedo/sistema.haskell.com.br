<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Email</title>
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
            background-color: #0d6efd;
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
        .code-box {
            background-color: white;
            border: 2px solid #0d6efd;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #0d6efd;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Verificação de Email</h1>
    </div>

    <div class="content">
        <p>Olá, <strong>{{ $distributor->trade_name }}</strong>!</p>

        <p>Obrigado por se cadastrar como distribuidor em nosso sistema.</p>

        <p>Para completar seu cadastro, utilize o código de verificação abaixo:</p>

        <div class="code-box">
            <div class="code">{{ $code }}</div>
        </div>

        <p>Este código é válido e deve ser inserido na página de verificação para confirmar seu email.</p>

        <p>Se você não solicitou este cadastro, por favor ignore este email.</p>

        <p><strong>Próximos passos:</strong></p>
        <ol>
            <li>Insira o código de verificação na página de confirmação</li>
            <li>Aguarde a análise do seu cadastro pela nossa equipe</li>
            <li>Você receberá um email quando seu cadastro for aprovado</li>
        </ol>

        <p>Atenciosamente,<br>
        <strong>Equipe Sistema de Distribuidores</strong></p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>&copy; {{ date('Y') }} Sistema de Distribuidores. Todos os direitos reservados.</p>
    </div>
</body>
</html>
