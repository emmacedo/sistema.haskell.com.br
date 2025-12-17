@extends('layouts.distributor')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="icon teal me-3">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="value">{{ $stats['total_cities'] }}</div>
                        <div class="label">Cidades Atendidas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="icon lime me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <div class="value">{{ $stats['total_sellers'] }}</div>
                        <div class="label">Vendedores</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="icon teal me-3">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <div class="value">{{ $stats['total_messages'] }}</div>
                        <div class="label">Mensagens Totais</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="icon pink me-3">
                        <i class="bi bi-envelope-exclamation"></i>
                    </div>
                    <div>
                        <div class="value">{{ $stats['unread_messages'] }}</div>
                        <div class="label">Não Lidas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Últimas Mensagens -->
        <div class="col-lg-8 mb-4">
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-envelope me-2"></i>Últimas Mensagens</h6>
                    <a href="{{ route('distributor.messages') }}" class="btn btn-sm btn-light">Ver Todas</a>
                </div>
                <div class="card-body p-0">
                    @if($recentMessages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Remetente</th>
                                        <th>Vendedor</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMessages as $message)
                                        <tr class="{{ !$message->read_at ? 'table-warning' : '' }}">
                                            <td>
                                                <strong>{{ $message->sender_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $message->sender_email }}</small>
                                            </td>
                                            <td>{{ $message->seller->name ?? '-' }}</td>
                                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($message->read_at)
                                                    <span class="badge bg-success">Lida</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Nova</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('distributor.messages.show', $message->id) }}"
                                                   class="btn btn-sm btn-outline-haskell">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-envelope-open text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Nenhuma mensagem recebida ainda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações Rápidas -->
        <div class="col-lg-4 mb-4">
            <div class="table-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-building me-2"></i>Sua Empresa</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>{{ $distributor->trade_name }}</strong>
                    </p>
                    <p class="mb-2 text-muted small">
                        {{ $distributor->company_name }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2 text-muted"></i>
                        {{ $distributor->email }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-telephone me-2 text-muted"></i>
                        {{ $distributor->phone }}
                    </p>
                    @if($distributor->whatsapp)
                        <p class="mb-0">
                            <i class="bi bi-whatsapp me-2 text-muted"></i>
                            {{ $distributor->whatsapp }}
                        </p>
                    @endif
                    <hr>
                    <a href="{{ route('distributor.profile.edit') }}" class="btn btn-haskell btn-sm w-100">
                        <i class="bi bi-pencil me-2"></i>Editar Dados
                    </a>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('distributor.sellers.create') }}" class="btn btn-outline-haskell btn-sm w-100 mb-2">
                        <i class="bi bi-person-plus me-2"></i>Adicionar Vendedor
                    </a>
                    <a href="{{ route('distributor.cities') }}" class="btn btn-outline-haskell btn-sm w-100">
                        <i class="bi bi-geo-alt me-2"></i>Gerenciar Cidades
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
