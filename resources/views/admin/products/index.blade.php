@extends('adminlte::page')

@section('title', 'Produtos de Interesse')

@section('content_header')
    <h1>Produtos de Interesse</h1>
@stop

@section('content')
    <x-adminlte-card>
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-input
                        name="search"
                        placeholder="Buscar por nome..."
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
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Nome</th>
                        <th width="100">Ordem</th>
                        <th width="100">Status</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->order }}</td>
                            <td>
                                @if($product->active)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.toggle-status', $product) }}"
                                          method="POST"
                                          style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm {{ $product->active ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $product->active ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas {{ $product->active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteProduct({{ $product->id }})"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $product->id }}"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </x-adminlte-card>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteProduct(id) {
        Swal.fire({
            title: 'Excluir Produto',
            text: 'Tem certeza que deseja excluir este produto?',
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
