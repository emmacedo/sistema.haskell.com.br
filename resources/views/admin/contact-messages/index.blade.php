@extends('adminlte::page')

@section('title', 'Mensagens de Contato')

@section('content_header')
    <h1>Mensagens de Contato</h1>
@stop

@section('content')
    <x-adminlte-card>
        <form method="GET" action="{{ route('contact-messages.index') }}" class="mb-3">
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
                    <x-adminlte-select name="status">
                        <option value="">Todas as mensagens</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Não lidas</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lidas</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('contact-messages.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-redo"></i> Limpar
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="messages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Remetente</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Distribuidor</th>
                        <th>Cidade</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="{{ !$message->isRead() ? 'table-warning' : '' }}">
                            <td>{{ $message->id }}</td>
                            <td>
                                @if($message->isRead())
                                    <span class="badge badge-success">Lida</span>
                                @else
                                    <span class="badge badge-warning">Não lida</span>
                                @endif
                            </td>
                            <td>{{ $message->sender_name }}</td>
                            <td>{{ $message->sender_email }}</td>
                            <td>{{ $message->sender_phone ?? '-' }}</td>
                            <td>{{ $message->distributor->trade_name ?? '-' }}</td>
                            <td>
                                @if($message->city)
                                    {{ $message->city->name }} - {{ $message->city->state->uf }}
                                @elseif($message->sender_city)
                                    {{ $message->sender_city }} - {{ $message->sender_state }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('contact-messages.show', $message) }}"
                                       class="btn btn-sm btn-info"
                                       title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$message->isRead())
                                        <form action="{{ route('contact-messages.mark-as-read', $message) }}"
                                              method="POST"
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-success"
                                                    title="Marcar como lida">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteMessage({{ $message->id }})"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $message->id }}"
                                      action="{{ route('contact-messages.destroy', $message) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Nenhuma mensagem encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $messages->links() }}
        </div>
    </x-adminlte-card>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteMessage(id) {
        Swal.fire({
            title: 'Excluir Mensagem',
            text: 'Tem certeza que deseja excluir esta mensagem? Esta ação não pode ser desfeita!',
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
