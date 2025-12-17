@extends('layouts.app')

@section('title', 'Email Verificado')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>

                    <h1 class="mb-3">Email Verificado com Sucesso!</h1>

                    <p class="lead mb-4">
                        Parabéns, <strong>{{ $distributorName }}</strong>!
                    </p>

                    <div class="alert alert-success">
                        <p class="mb-0">
                            <i class="bi bi-envelope-check"></i>
                            Seu email foi verificado com sucesso!
                        </p>
                    </div>

                    <!-- Informações sobre próximos passos -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">O que acontece agora?</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>1. Análise</h6>
                                    <p class="small text-muted">
                                        Nossa equipe irá analisar seu cadastro
                                    </p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>2. Notificação</h6>
                                    <p class="small text-muted">
                                        Você receberá um email com o resultado
                                    </p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="text-primary mb-2">
                                        <i class="bi bi-rocket-takeoff" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6>3. Ativação</h6>
                                    <p class="small text-muted">
                                        Após aprovação, você estará visível no sistema
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações adicionais -->
                    <div class="card mt-4">
                        <div class="card-body text-start">
                            <h6 class="mb-3">Informações Importantes:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check text-success"></i>
                                    Seu cadastro está <strong>em análise</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check text-success"></i>
                                    O processo de aprovação pode levar até <strong>48 horas</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check text-success"></i>
                                    Você receberá um email quando houver atualização
                                </li>
                                <li>
                                    <i class="bi bi-check text-success"></i>
                                    Após aprovação, clientes poderão encontrar sua empresa
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Benefícios -->
                    <div class="card mt-4 bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">Após a aprovação você terá:</h6>
                            <div class="row">
                                <div class="col-md-6 text-start mb-2">
                                    <i class="bi bi-star-fill text-warning"></i> Visibilidade nacional
                                </div>
                                <div class="col-md-6 text-start mb-2">
                                    <i class="bi bi-star-fill text-warning"></i> Leads qualificados
                                </div>
                                <div class="col-md-6 text-start mb-2">
                                    <i class="bi bi-star-fill text-warning"></i> Contato direto com clientes
                                </div>
                                <div class="col-md-6 text-start mb-2">
                                    <i class="bi bi-star-fill text-warning"></i> Presença digital gratuita
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('search.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-house"></i> Ir para Página Inicial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
