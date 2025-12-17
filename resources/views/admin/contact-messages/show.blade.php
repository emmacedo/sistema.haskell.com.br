@extends('adminlte::page')

@section('title', 'Detalhes da Mensagem')

@section('content_header')
    <h1>Detalhes da Mensagem</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Mensagem" theme="primary" icon="fas fa-envelope">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @if($contactMessage->isRead())
                            <span class="badge badge-success">Mensagem Lida</span>
                        @else
                            <span class="badge badge-warning">Mensagem Não Lida</span>
                        @endif
                        @if($contactMessage->read_at)
                            <small class="text-muted ml-2">Lida em {{ $contactMessage->read_at->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>De:</strong> {{ $contactMessage->sender_name }}</p>
                        <p><strong>E-mail:</strong> {{ $contactMessage->sender_email }}</p>
                        <p><strong>Telefone:</strong> {{ $contactMessage->sender_phone ?? 'Não informado' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Data:</strong> {{ $contactMessage->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>ID:</strong> #{{ $contactMessage->id }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5>Mensagem:</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                {{ $contactMessage->message }}
                            </div>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>

            {{-- Card de informações do distribuidor (com verificação de null) --}}
            @if($contactMessage->distributor)
                <x-adminlte-card title="Informações do Distribuidor" theme="info" icon="fas fa-building">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Razão Social:</strong> {{ $contactMessage->distributor->company_name }}</p>
                            <p><strong>Nome Fantasia:</strong> {{ $contactMessage->distributor->trade_name }}</p>
                            <p><strong>CNPJ:</strong> {{ $contactMessage->distributor->cnpj }}</p>
                            <p><strong>E-mail:</strong> {{ $contactMessage->distributor->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Telefone:</strong> {{ $contactMessage->distributor->phone }}</p>
                            <p><strong>WhatsApp:</strong> {{ $contactMessage->distributor->whatsapp ?? 'Não informado' }}</p>
                            <p><strong>Status:</strong>
                                @if($contactMessage->distributor->status === 'approved')
                                    <span class="badge badge-success">Aprovado</span>
                                @elseif($contactMessage->distributor->status === 'pending')
                                    <span class="badge badge-warning">Pendente</span>
                                @else
                                    <span class="badge badge-danger">Rejeitado</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($contactMessage->distributor->cities && $contactMessage->distributor->cities->count() > 0)
                        <hr>
                        <h5>Cidades Atendidas:</h5>
                        <div class="row">
                            @foreach($contactMessage->distributor->cities->take(10) as $city)
                                <div class="col-md-6">
                                    <span class="badge badge-secondary">{{ $city->name }} - {{ $city->state->uf }}</span>
                                </div>
                            @endforeach
                            @if($contactMessage->distributor->cities->count() > 10)
                                <div class="col-md-12 mt-2">
                                    <small class="text-muted">E mais {{ $contactMessage->distributor->cities->count() - 10 }} cidades...</small>
                                </div>
                            @endif
                        </div>
                    @endif
                </x-adminlte-card>
            @endif

            {{-- Card de localização do remetente - suporta tanto o relacionamento city quanto os campos de texto --}}
            <x-adminlte-card title="Localização do Remetente" theme="success" icon="fas fa-map-marker-alt">
                @if($contactMessage->city)
                    {{-- Mensagens antigas com relacionamento city --}}
                    <p><strong>Cidade:</strong> {{ $contactMessage->city->name }}</p>
                    <p><strong>Estado:</strong> {{ $contactMessage->city->state->name }} ({{ $contactMessage->city->state->uf }})</p>
                    <p><strong>Código IBGE:</strong> {{ $contactMessage->city->ibge_code }}</p>
                @elseif($contactMessage->sender_city || $contactMessage->sender_state)
                    {{-- Mensagens novas com campos de texto --}}
                    <p><strong>Cidade:</strong> {{ $contactMessage->sender_city ?? 'Não informada' }}</p>
                    <p><strong>Estado:</strong> {{ $contactMessage->sender_state ?? 'Não informado' }}</p>
                @else
                    <p class="text-muted">Localização não informada.</p>
                @endif
            </x-adminlte-card>

            {{-- Card de produto de interesse (se houver) --}}
            @if($contactMessage->product)
                <x-adminlte-card title="Produto de Interesse" theme="warning" icon="fas fa-box">
                    <p><strong>Produto:</strong> {{ $contactMessage->product->name }}</p>
                </x-adminlte-card>
            @endif

            {{-- Card de vendedor específico (se houver) --}}
            @if($contactMessage->seller)
                <x-adminlte-card title="Vendedor Contatado" theme="info" icon="fas fa-user-tie">
                    <p><strong>Nome:</strong> {{ $contactMessage->seller->name }}</p>
                    @if($contactMessage->seller->email)
                        <p><strong>E-mail:</strong> {{ $contactMessage->seller->email }}</p>
                    @endif
                    @if($contactMessage->seller->phone)
                        <p><strong>Telefone:</strong> {{ $contactMessage->seller->phone }}</p>
                    @endif
                </x-adminlte-card>
            @endif
        </div>

        <div class="col-md-4">
            <x-adminlte-card title="Ações" theme="secondary" icon="fas fa-cog">
                @if(!$contactMessage->isRead())
                    <form action="{{ route('contact-messages.mark-as-read', $contactMessage) }}"
                          method="POST"
                          class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-check"></i> Marcar como Lida
                        </button>
                    </form>
                @endif

                @if($contactMessage->distributor)
                    <a href="{{ route('distributors.show', $contactMessage->distributor) }}"
                       class="btn btn-info btn-block">
                        <i class="fas fa-building"></i> Ver Distribuidor
                    </a>
                @endif

                <a href="{{ route('contact-messages.index') }}"
                   class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>

                <hr>

                <form id="delete-form"
                      action="{{ route('contact-messages.destroy', $contactMessage) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="btn btn-danger btn-block"
                            onclick="deleteMessage()">
                        <i class="fas fa-trash"></i> Excluir Mensagem
                    </button>
                </form>
            </x-adminlte-card>

            <x-adminlte-card title="Informações Adicionais" theme="warning" icon="fas fa-info-circle">
                <p><strong>ID da Mensagem:</strong> #{{ $contactMessage->id }}</p>
                <p><strong>Recebida em:</strong><br>{{ $contactMessage->created_at->format('d/m/Y H:i:s') }}</p>
                @if($contactMessage->read_at)
                    <p><strong>Lida em:</strong><br>{{ $contactMessage->read_at->format('d/m/Y H:i:s') }}</p>
                @endif
            </x-adminlte-card>

            <x-adminlte-card title="Contato Rápido" theme="primary" icon="fas fa-phone">
                <p><strong>E-mail:</strong><br>
                    <a href="mailto:{{ $contactMessage->sender_email }}">{{ $contactMessage->sender_email }}</a>
                </p>
                @if($contactMessage->sender_phone)
                    <p><strong>Telefone:</strong><br>
                        <a href="tel:{{ $contactMessage->sender_phone }}">{{ $contactMessage->sender_phone }}</a>
                    </p>
                @endif
                {{-- WhatsApp do distribuidor (se existir distribuidor e whatsapp) --}}
                @if($contactMessage->distributor && $contactMessage->distributor->whatsapp)
                    <p><strong>WhatsApp do Distribuidor:</strong><br>
                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $contactMessage->distributor->whatsapp) }}"
                           target="_blank">
                            {{ $contactMessage->distributor->whatsapp }}
                        </a>
                    </p>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deleteMessage() {
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
