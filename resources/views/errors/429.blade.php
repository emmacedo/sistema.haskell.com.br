@extends('layouts.app')

@section('title', 'Muitas Tentativas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-clock-history text-warning" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="mb-3">Muitas Tentativas</h1>

                    <p class="lead mb-4">
                        Você realizou muitas tentativas em um curto período de tempo.
                    </p>

                    <div class="alert alert-warning">
                        <p class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Por favor, aguarde alguns minutos e tente novamente.
                        </p>
                    </div>

                    <div class="alert alert-light border">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Essa limitação existe para proteger o sistema contra uso indevido.
                            Se você acredita que isso é um erro, entre em contato com nosso suporte.
                        </small>
                    </div>

                    <div class="mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <a href="{{ route('search.index') }}" class="btn btn-primary ms-2">
                            <i class="bi bi-house"></i> Ir para Busca
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
