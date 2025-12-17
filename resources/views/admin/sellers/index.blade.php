@extends('adminlte::page')

@section('title', 'Vendedores')

@section('content_header')
    <h1>Vendedores</h1>
@stop

@section('content')
    <x-adminlte-card>
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('sellers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Vendedor
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('sellers.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-input
                        name="search"
                        placeholder="Buscar por nome, e-mail, telefone..."
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
                    <x-adminlte-select name="distributor_id">
                        <option value="">Todos os distribuidores</option>
                        @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->trade_name }}
                            </option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sellers.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="sellers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>WhatsApp</th>
                        <th>Cargo</th>
                        <th>Distribuidor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $seller)
                        <tr>
                            <td>{{ $seller->id }}</td>
                            <td>{{ $seller->name }}</td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->phone }}</td>
                            <td>{{ $seller->whatsapp ?? '-' }}</td>
                            <td>{{ $seller->position ?? '-' }}</td>
                            <td>{{ $seller->distributor->trade_name }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sellers.edit', $seller) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteSeller({{ $seller->id }})"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $seller->id }}"
                                      action="{{ route('sellers.destroy', $seller) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Nenhum vendedor encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $sellers->links() }}
        </div>
    </x-adminlte-card>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteSeller(id) {
        Swal.fire({
            title: 'Excluir Vendedor',
            text: 'Tem certeza que deseja excluir este vendedor? Esta ação não pode ser desfeita!',
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
