@extends('adminlte::page')

@section('title', 'Detalhes do Distribuidor')

@section('content_header')
    <h1>Detalhes do Distribuidor</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Informações Gerais" theme="primary" icon="fas fa-info-circle">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $distributor->id }}</p>
                        <p><strong>Razão Social:</strong> {{ $distributor->company_name }}</p>
                        <p><strong>Nome Fantasia:</strong> {{ $distributor->trade_name }}</p>
                        <p><strong>CNPJ:</strong> {{ $distributor->cnpj }}</p>
                        <p><strong>E-mail:</strong> {{ $distributor->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Telefone:</strong> {{ $distributor->phone }}</p>
                        <p><strong>Telefone 2:</strong> {{ $distributor->phone2 ?? 'Não informado' }}</p>
                        <p><strong>WhatsApp:</strong> {{ $distributor->whatsapp ?? 'Não informado' }}</p>
                        <p><strong>Website:</strong>
                            @if($distributor->website)
                                <a href="{{ $distributor->website }}" target="_blank">{{ $distributor->website }}</a>
                            @else
                                Não informado
                            @endif
                        </p>
                        <p><strong>Cadastrado em:</strong> {{ $distributor->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Atualizado em:</strong> {{ $distributor->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Endereço:</strong><br>
                        @if($distributor->cep || $distributor->logradouro)
                            {{ $distributor->logradouro }}{{ $distributor->numero ? ', ' . $distributor->numero : '' }}{{ $distributor->complemento ? ' - ' . $distributor->complemento : '' }}<br>
                            {{ $distributor->bairro }}<br>
                            {{ $distributor->cidade }} - {{ $distributor->estado }}<br>
                            CEP: {{ $distributor->cep }}
                        @else
                            Endereço não informado
                        @endif
                        </p>
                    </div>
                </div>
            </x-adminlte-card>

            <x-adminlte-card title="Cidades Atendidas" theme="info" icon="fas fa-map-marker-alt">
                @if($distributor->cities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Cidade</th>
                                    <th>Estado</th>
                                    <th>Código IBGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distributor->cities as $city)
                                    <tr>
                                        <td>{{ $city->name }}</td>
                                        <td>{{ $city->state->name }} ({{ $city->state->uf }})</td>
                                        <td>{{ $city->ibge_code }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhuma cidade cadastrada.</p>
                @endif
            </x-adminlte-card>

            <x-adminlte-card title="Vendedores" theme="success" icon="fas fa-users">
                @if($distributor->sellers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                    <th>Cargo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distributor->sellers as $seller)
                                    <tr>
                                        <td>{{ $seller->name }}</td>
                                        <td>{{ $seller->email }}</td>
                                        <td>{{ $seller->phone }}</td>
                                        <td>{{ $seller->position ?? 'Não informado' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Nenhum vendedor cadastrado.</p>
                @endif
            </x-adminlte-card>

            <x-adminlte-card title="Mensagens de Contato" theme="warning" icon="fas fa-comments">
                @if($distributor->contactMessages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Remetente</th>
                                    <th>E-mail</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distributor->contactMessages->take(5) as $message)
                                    <tr>
                                        <td>{{ $message->sender_name }}</td>
                                        <td>{{ $message->sender_email }}</td>
                                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($message->isRead())
                                                <span class="badge badge-success">Lida</span>
                                            @else
                                                <span class="badge badge-warning">Não lida</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($distributor->contactMessages->count() > 5)
                        <p class="text-muted"><small>Mostrando as 5 mensagens mais recentes.</small></p>
                    @endif
                @else
                    <p class="text-muted">Nenhuma mensagem de contato.</p>
                @endif
            </x-adminlte-card>
        </div>

        <div class="col-md-4">
            <x-adminlte-card title="Status" theme="secondary" icon="fas fa-check-circle">
                <div class="text-center">
                    @if($distributor->status === 'pending')
                        <h3><span class="badge badge-warning">Pendente</span></h3>
                        <p class="text-muted mt-3">Aguardando aprovação</p>
                    @elseif($distributor->status === 'approved')
                        <h3><span class="badge badge-success">Aprovado</span></h3>
                        <p class="text-muted mt-3">Distribuidor ativo no sistema</p>
                    @else
                        <h3><span class="badge badge-danger">Rejeitado</span></h3>
                        <p class="text-muted mt-3">Distribuidor não aprovado</p>
                    @endif
                </div>

                <hr>

                <div class="mt-3">
                    @if($distributor->status === 'pending')
                        <form action="{{ route('distributors.approve', $distributor) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Tem certeza que deseja aprovar este distribuidor?')">
                                <i class="fas fa-check"></i> Aprovar
                            </button>
                        </form>

                        <button type="button" class="btn btn-warning btn-block" onclick="rejectDistributor()">
                            <i class="fas fa-times"></i> Rejeitar
                        </button>
                    @endif

                    <a href="{{ route('distributors.edit', $distributor) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Editar
                    </a>

                    <a href="{{ route('distributors.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>

                    <form id="delete-form" action="{{ route('distributors.destroy', $distributor) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-block" onclick="deleteDistributor()">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </x-adminlte-card>

            <x-adminlte-card title="Estatísticas" theme="info" icon="fas fa-chart-bar">
                <p><strong>Total de Cidades:</strong> {{ $distributor->cities->count() }}</p>
                <p><strong>Total de Vendedores:</strong> {{ $distributor->sellers->count() }}</p>
                <p><strong>Total de Mensagens:</strong> {{ $distributor->contactMessages->count() }}</p>
                <p><strong>Mensagens Não Lidas:</strong> {{ $distributor->contactMessages->where('read_at', null)->count() }}</p>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function rejectDistributor() {
        Swal.fire({
            title: 'Rejeitar Distribuidor',
            text: 'Tem certeza que deseja rejeitar este distribuidor?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Rejeitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('distributors.reject', $distributor) }}';

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

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deleteDistributor() {
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
                document.getElementById('delete-form').submit();
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
