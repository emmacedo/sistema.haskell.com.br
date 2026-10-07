@extends('layouts.app')

@section('title', 'Sessão Expirada')

@php
    // Em áreas autenticadas (admin, painel do distribuidor, login) o caminho natural
    // é entrar novamente. Nas páginas públicas (busca, cadastro, contato) basta tentar
    // de novo — nunca direcionar o visitante do site ao login/cadastro de distribuidor.
    $isAdminArea = request()->is('admin*');
    $isDistributorArea = request()->is('painel*') || request()->is('login*') || request()->is('logout');
    $showLogin = $isAdminArea || $isDistributorArea;
    $loginUrl = $isAdminArea ? route('admin.login') : route('distributor.login');
    $retryUrl = url()->previous() ?: route('search.index');
@endphp

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

                    @if ($showLogin)
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
                            <a href="{{ $loginUrl }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Fazer Login
                            </a>
                            <a href="{{ route('search.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Ir para Busca
                            </a>
                        </div>
                    @else
                        <p class="lead mb-4">
                            A página ficou aberta por muito tempo e expirou. Clique abaixo para tentar novamente.
                        </p>

                        <div class="alert alert-warning">
                            <p class="mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                Se você estava preenchendo um formulário, os dados podem ter sido perdidos
                                e será necessário preencher novamente.
                            </p>
                        </div>

                        <div class="mt-4 d-flex justify-content-center gap-2">
                            <a href="{{ $retryUrl }}" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Tentar novamente
                            </a>
                            <a href="{{ route('search.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Ir para Busca
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
