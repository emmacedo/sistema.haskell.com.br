@extends('layouts.app')

@section('title', 'Sessão Expirada')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-shield-exclamation text-warning" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="mb-3">Sessão Expirada</h1>

                    <p class="lead mb-4">
                        Sua sessão expirou por inatividade. Por favor, faça login novamente para continuar.
                    </p>

                    <div class="alert alert-warning">
                        <p class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Se você estava preenchendo um formulário, os dados podem ter sido perdidos.
                            Após o login, será necessário preencher novamente.
                        </p>
                    </div>

                    <div class="mt-4 d-flex justify-content-center gap-2">
                        <a href="{{ route('distributor.login') }}" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Fazer Login
                        </a>
                        <a href="{{ route('search.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> Ir para Busca
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
