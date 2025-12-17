<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Distribuidor | Haskell Cosméticos</title>
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
                            <p class="text-muted">Acesso para Distribuidores</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('distributor.login.send') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="seu@email.com" required autofocus>
                                <div class="form-text">
                                    Enviaremos um código de acesso para este e-mail
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Enviar Código
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2">
                                <small class="text-muted">Ainda não é cadastrado?</small>
                            </p>
                            <a href="{{ route('registration.create') }}" class="btn btn-outline-secondary">
                                Cadastre-se como Distribuidor
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
