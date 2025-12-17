@extends('layouts.distributor')

@section('title', 'Mensagem')
@section('page-title', 'Detalhes da Mensagem')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-envelope-open me-2"></i>Mensagem</h6>
                    <span class="badge {{ $message->read_at ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $message->read_at ? 'Lida' : 'Nova' }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Cabeçalho da mensagem -->
                    <div class="border-bottom pb-3 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>De:</strong> {{ $message->sender_name }}
                                </p>
                                <p class="mb-1 text-muted">
                                    <i class="bi bi-envelope me-1"></i>{{ $message->sender_email }}
                                </p>
                                @if($message->sender_phone)
                                    <p class="mb-0 text-muted">
                                        <i class="bi bi-telephone me-1"></i>{{ $message->sender_phone }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1 text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $message->created_at->format('d/m/Y H:i') }}
                                </p>
                                @if($message->read_at)
                                    <p class="mb-0 text-muted small">
                                        Lida em {{ $message->read_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo da mensagem -->
                    <div class="message-content bg-light p-4 rounded">
                        {!! nl2br(e($message->message)) !!}
                    </div>

                    <hr class="my-4">

                    <!-- Ações -->
                    <div class="d-flex gap-2">
                        <a href="mailto:{{ $message->sender_email }}" class="btn btn-haskell">
                            <i class="bi bi-reply me-2"></i>Responder por E-mail
                        </a>
                        @if($message->sender_phone)
                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $message->sender_phone) }}"
                               class="btn btn-success" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        @endif
                        <a href="{{ route('distributor.messages') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informações do Vendedor -->
            @if($message->seller)
                <div class="table-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person me-2"></i>Vendedor Destinatário</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>{{ $message->seller->name }}</strong>
                        </p>
                        @if($message->seller->email)
                            <p class="mb-2 text-muted small">
                                <i class="bi bi-envelope me-2"></i>{{ $message->seller->email }}
                            </p>
                        @endif
                        @if($message->seller->phone)
                            <p class="mb-2 text-muted small">
                                <i class="bi bi-telephone me-2"></i>{{ $message->seller->phone }}
                            </p>
                        @endif
                        @if($message->seller->position)
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-briefcase me-2"></i>{{ $message->seller->position }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Informações Adicionais -->
            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informações</h6>
                </div>
                <div class="card-body">
                    @if($message->city)
                        <p class="mb-2 small">
                            <strong>Cidade do Cliente:</strong><br>
                            {{ $message->city->name }} - {{ $message->city->state->uf ?? '' }}
                        </p>
                    @endif
                    @if($message->product)
                        <p class="mb-2 small">
                            <strong>Produto de Interesse:</strong><br>
                            {{ $message->product->name ?? '-' }}
                        </p>
                    @endif
                    <p class="mb-0 small text-muted">
                        <strong>ID da Mensagem:</strong> #{{ $message->id }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
