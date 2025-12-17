@extends('layouts.app')

@section('title', 'Cadastro Realizado')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="mb-3">Cadastro Realizado com Sucesso!</h1>

                    <p class="lead mb-4">
                        Obrigado por se cadastrar como distribuidor em nosso sistema.
                    </p>

                    @if(session('email_send_failed'))
                        <div class="alert alert-warning">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Atenção!</h5>
                            <p class="mb-2">
                                Houve um problema ao enviar o email de verificação.
                            </p>
                            <p class="mb-0">
                                Por favor, clique em <strong>"Reenviar Código"</strong> abaixo para tentar novamente.
                                Se o problema persistir, entre em contato com nosso suporte.
                            </p>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">
                                <i class="bi bi-envelope"></i>
                                Enviamos um <strong>código de verificação</strong> para o email:
                                <br>
                                <strong>{{ $email }}</strong>
                            </p>
                        </div>
                    @endif

                    <div class="alert alert-light border">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i>
                            <strong>Importante:</strong> O código de verificação expira em <strong>24 horas</strong>.
                            Verifique sua caixa de entrada e também a pasta de spam.
                        </small>
                    </div>

                    <!-- Formulário de verificação -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Verificar Email</h5>
                        </div>
                        <div class="card-body">
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

                            <form action="{{ route('registration.verify') }}" method="POST">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">

                                <div class="mb-3">
                                    <label for="code" class="form-label">Digite o código de verificação</label>
                                    <input type="text"
                                           class="form-control form-control-lg text-center"
                                           id="code"
                                           name="code"
                                           maxlength="6"
                                           placeholder="XXXXXX"
                                           style="letter-spacing: 5px; font-size: 24px;"
                                           required
                                           autofocus>
                                    <small class="text-muted">Digite o código de 6 caracteres que você recebeu por email</small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-check-circle"></i> Verificar Email
                                </button>
                            </form>

                            <hr class="my-4">

                            <!-- Reenviar código -->
                            <form action="{{ route('registration.resend') }}" method="POST">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">

                                <p class="text-muted mb-2">Não recebeu o código?</p>
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reenviar Código
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Próximos passos -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Próximos Passos</h6>
                        </div>
                        <div class="card-body text-start">
                            <ol>
                                <li class="mb-2">
                                    <strong>Verifique seu email</strong> - Digite o código de verificação acima
                                </li>
                                <li class="mb-2">
                                    <strong>Aguarde aprovação</strong> - Nossa equipe irá analisar seu cadastro
                                </li>
                                <li class="mb-2">
                                    <strong>Receba confirmação</strong> - Você receberá um email quando seu cadastro for aprovado
                                </li>
                                <li>
                                    <strong>Comece a receber leads</strong> - Após aprovação, clientes poderão encontrar sua empresa
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('search.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Voltar para Busca
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Formatar código em maiúsculas
    $('#code').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
});
</script>
@endsection
