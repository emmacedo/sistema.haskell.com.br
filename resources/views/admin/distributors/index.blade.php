@extends('adminlte::page')

@section('title', 'Distribuidores')

@section('content_header')
    <h1>Distribuidores</h1>
@stop

@section('content')
    <x-adminlte-card>
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('distributors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Distribuidor
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('distributors.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-input
                        name="search"
                        placeholder="Buscar por nome, CNPJ, e-mail..."
                        value="{{ request('search') }}"
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-search"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="status">
                        <option value="">Todos os status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('distributors.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </div>
        </form>

        {{-- Lista de distribuidores em cards --}}
        <div class="distributors-list">
            @forelse($distributors as $distributor)
                <div class="card mb-2 distributor-card {{ $distributor->status === 'pending' ? 'border-left-warning' : ($distributor->status === 'approved' ? 'border-left-success' : 'border-left-danger') }}">
                    <div class="card-body py-2 px-3">
                        <div class="row align-items-center">
                            {{-- Coluna: Status e ID --}}
                            <div class="col-auto text-center" style="min-width: 80px;">
                                @if($distributor->status === 'pending')
                                    <span class="badge badge-warning d-block mb-1">Pendente</span>
                                @elseif($distributor->status === 'approved')
                                    <span class="badge badge-success d-block mb-1">Aprovado</span>
                                @else
                                    <span class="badge badge-danger d-block mb-1">Rejeitado</span>
                                @endif
                                <small class="text-muted">#{{ $distributor->id }}</small>
                            </div>

                            {{-- Coluna: Informações principais --}}
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-5">
                                        <strong class="d-block text-truncate" title="{{ $distributor->trade_name }}">
                                            {{ $distributor->trade_name }}
                                        </strong>
                                        <small class="text-muted text-truncate d-block" title="{{ $distributor->company_name }}">
                                            {{ $distributor->company_name }}
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">CNPJ</small>
                                        <span class="small">{{ $distributor->cnpj }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex justify-content-around text-center">
                                            <div>
                                                <small class="text-muted d-block">Cidades</small>
                                                <span class="badge badge-secondary">{{ $distributor->cities->count() }}</span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Vendedores</small>
                                                <span class="badge badge-secondary">{{ $distributor->sellers->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Coluna: Ações --}}
                            <div class="col-auto">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('distributors.show', $distributor) }}"
                                       class="btn btn-sm btn-info"
                                       title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('distributors.edit', $distributor) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($distributor->status === 'pending')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="approveDistributor({{ $distributor->id }})"
                                                title="Aprovar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-warning"
                                                onclick="rejectDistributor({{ $distributor->id }})"
                                                title="Rejeitar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteDistributor({{ $distributor->id }})"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                {{-- Formulários ocultos para ações --}}
                                <form id="approve-form-{{ $distributor->id }}"
                                      action="{{ route('distributors.approve', $distributor) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                                <form id="delete-form-{{ $distributor->id }}"
                                      action="{{ route('distributors.destroy', $distributor) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Nenhum distribuidor encontrado.
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $distributors->links() }}
        </div>
    </x-adminlte-card>
@stop

@section('css')
<style>
    /* Cards de distribuidores compactos */
    .distributor-card {
        transition: all 0.2s ease;
        border-left-width: 4px !important;
    }
    .distributor-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .border-left-warning {
        border-left-color: #ffc107 !important;
    }
    .border-left-success {
        border-left-color: #28a745 !important;
    }
    .border-left-danger {
        border-left-color: #dc3545 !important;
    }
    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .distributor-card .row {
            flex-direction: column;
        }
        .distributor-card .col-auto {
            width: 100%;
            margin-top: 10px;
        }
        .distributor-card .btn-group {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function approveDistributor(id) {
        Swal.fire({
            title: 'Aprovar Distribuidor',
            text: 'Tem certeza que deseja aprovar este distribuidor? Um e-mail de boas-vindas será enviado.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, aprovar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form-' + id).submit();
            }
        });
    }

    function rejectDistributor(id) {
        Swal.fire({
            title: 'Rejeitar Distribuidor',
            html: '<textarea id="rejection_reason" class="form-control" placeholder="Motivo da rejeição..." rows="4"></textarea>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Rejeitar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const reason = document.getElementById('rejection_reason').value;
                if (!reason) {
                    Swal.showValidationMessage('Por favor, informe o motivo da rejeição');
                    return false;
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/distributors/' + id + '/reject';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);

                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = result.value;
                form.appendChild(reasonInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deleteDistributor(id) {
        Swal.fire({
            title: 'Excluir Distribuidor',
            text: 'Tem certeza que deseja excluir este distribuidor? Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: '{{ session('error') }}',
        });
    @endif
</script>
@stop
