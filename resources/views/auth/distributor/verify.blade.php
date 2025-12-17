<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Código | Haskell Cosméticos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/haskell-admin.css') }}">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2><strong>Haskell</strong> Distribuidor</h2>
                            <p class="text-muted">Verificação de Código</p>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <small>Enviamos um código de 6 dígitos para <strong>{{ $email }}</strong></small>
                        </div>

                        <form action="{{ route('distributor.login.verify') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="code" class="form-label">Código de Verificação</label>
                                <input type="text" class="form-control form-control-lg text-center @error('code') is-invalid @enderror"
                                       id="code" name="code" maxlength="6"
                                       placeholder="000000" required autofocus
                                       style="font-size: 2rem; letter-spacing: 10px;">
                                <div class="form-text">
                                    Digite o código de 6 dígitos que você recebeu por e-mail
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Verificar e Entrar
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2">
                                <small class="text-muted">Não recebeu o código?</small>
                            </p>
                            <form action="{{ route('distributor.login.resend') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    Reenviar Código
                                </button>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('distributor.login') }}" class="text-muted text-decoration-none">
                                <small>← Voltar para o login</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-formata o código enquanto digita
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
