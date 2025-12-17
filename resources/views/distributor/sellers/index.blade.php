@extends('layouts.distributor')

@section('title', 'Vendedores')
@section('page-title', 'Vendedores')

@section('content')
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-people me-2"></i>Meus Vendedores</h6>
            <a href="{{ route('distributor.sellers.create') }}" class="btn btn-sm btn-light">
                <i class="bi bi-plus-lg me-1"></i>Novo Vendedor
            </a>
        </div>
        <div class="card-body">
            @if($sellers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Cargo</th>
                                <th style="width: 150px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $seller)
                                <tr>
                                    <td>
                                        <strong>{{ $seller->name }}</strong>
                                    </td>
                                    <td>{{ $seller->email }}</td>
                                    <td>
                                        {{ $seller->phone ?? '-' }}
                                        @if($seller->whatsapp)
                                            <br>
                                            <small class="text-success">
                                                <i class="bi bi-whatsapp"></i> {{ $seller->whatsapp }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $seller->position ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('distributor.sellers.edit', $seller->id) }}"
                                               class="btn btn-outline-haskell" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-outline-danger"
                                                    title="Excluir"
                                                    onclick="confirmDelete({{ $seller->id }}, '{{ $seller->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $sellers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Nenhum vendedor cadastrado.</p>
                    <a href="{{ route('distributor.sellers.create') }}" class="btn btn-haskell">
                        <i class="bi bi-plus-lg me-2"></i>Cadastrar Primeiro Vendedor
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Formulários de exclusão (fora da tabela para evitar conflitos) -->
    @foreach($sellers as $seller)
        <form id="delete-form-{{ $seller->id }}"
              action="{{ route('distributor.sellers.destroy', $seller->id) }}"
              method="POST"
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection

@section('scripts')
<script>
    function confirmDelete(sellerId, sellerName) {
        if (confirm('Tem certeza que deseja excluir o vendedor "' + sellerName + '"?\n\nEsta ação não pode ser desfeita.')) {
            document.getElementById('delete-form-' + sellerId).submit();
        }
    }
</script>
@endsection
