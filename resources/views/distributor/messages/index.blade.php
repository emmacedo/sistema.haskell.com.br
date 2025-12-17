@extends('layouts.distributor')

@section('title', 'Mensagens')
@section('page-title', 'Mensagens de Contato')

@section('content')
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-envelope me-2"></i>Mensagens Recebidas</h6>
            <span class="badge bg-light text-dark">{{ $messages->total() }} mensagens</span>
        </div>
        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('distributor.messages') }}" method="GET" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Não lidas</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lidas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Vendedor</label>
                        <select name="seller_id" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            @foreach($distributor->sellers as $seller)
                                <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-haskell">
                            <i class="bi bi-funnel me-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>

            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>Remetente</th>
                                <th>Vendedor</th>
                                <th>Assunto</th>
                                <th>Data</th>
                                <th style="width: 100px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ !$message->read_at ? 'table-warning' : '' }}">
                                    <td class="text-center">
                                        @if(!$message->read_at)
                                            <i class="bi bi-envelope-fill text-warning" title="Não lida"></i>
                                        @else
                                            <i class="bi bi-envelope-open text-muted" title="Lida"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $message->sender_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $message->sender_email }}</small>
                                    </td>
                                    <td>{{ $message->seller->name ?? '-' }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ Str::limit($message->message, 50) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $message->created_at->format('d/m/Y') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('distributor.messages.show', $message->id) }}"
                                           class="btn btn-sm btn-outline-haskell">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-envelope-open text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Nenhuma mensagem encontrada.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
