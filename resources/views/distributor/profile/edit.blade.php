@extends('layouts.distributor')

@section('title', 'Dados da Empresa')
@section('page-title', 'Dados da Empresa')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-building me-2"></i>Editar Dados da Empresa</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('distributor.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Dados que não podem ser alterados -->
                        <div class="alert alert-info mb-4">
                            <small>
                                <i class="bi bi-info-circle me-2"></i>
                                Alguns dados não podem ser alterados por aqui. Entre em contato com o suporte se necessário.
                            </small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Razão Social</label>
                                <input type="text" class="form-control" value="{{ $distributor->company_name }}" disabled>
                                <small class="text-muted">Não editável</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CNPJ</label>
                                <input type="text" class="form-control" value="{{ $distributor->cnpj }}" disabled>
                                <small class="text-muted">Não editável</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">E-mail</label>
                                <input type="email" class="form-control" value="{{ $distributor->email }}" disabled>
                                <small class="text-muted">Não editável</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nome Fantasia *</label>
                                <input type="text"
                                       name="trade_name"
                                       class="form-control @error('trade_name') is-invalid @enderror"
                                       value="{{ old('trade_name', $distributor->trade_name) }}"
                                       required>
                                @error('trade_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3"><i class="bi bi-telephone me-2"></i>Contato</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Telefone *</label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $distributor->phone) }}"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Telefone 2</label>
                                <input type="text"
                                       name="phone2"
                                       id="phone2"
                                       class="form-control @error('phone2') is-invalid @enderror"
                                       value="{{ old('phone2', $distributor->phone2) }}">
                                @error('phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">WhatsApp</label>
                                <input type="text"
                                       name="whatsapp"
                                       id="whatsapp"
                                       class="form-control @error('whatsapp') is-invalid @enderror"
                                       value="{{ old('whatsapp', $distributor->whatsapp) }}">
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Website</label>
                                <input type="url"
                                       name="website"
                                       class="form-control @error('website') is-invalid @enderror"
                                       value="{{ old('website', $distributor->website) }}"
                                       placeholder="https://exemplo.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Endereço</h6>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">CEP</label>
                                <input type="text"
                                       name="cep"
                                       id="cep"
                                       class="form-control @error('cep') is-invalid @enderror"
                                       value="{{ old('cep', $distributor->cep) }}">
                                @error('cep')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Logradouro</label>
                                <input type="text"
                                       name="logradouro"
                                       id="logradouro"
                                       class="form-control @error('logradouro') is-invalid @enderror"
                                       value="{{ old('logradouro', $distributor->logradouro) }}">
                                @error('logradouro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Número</label>
                                <input type="text"
                                       name="numero"
                                       id="numero"
                                       class="form-control @error('numero') is-invalid @enderror"
                                       value="{{ old('numero', $distributor->numero) }}">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Complemento</label>
                                <input type="text"
                                       name="complemento"
                                       class="form-control @error('complemento') is-invalid @enderror"
                                       value="{{ old('complemento', $distributor->complemento) }}">
                                @error('complemento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bairro</label>
                                <input type="text"
                                       name="bairro"
                                       id="bairro"
                                       class="form-control @error('bairro') is-invalid @enderror"
                                       value="{{ old('bairro', $distributor->bairro) }}">
                                @error('bairro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cidade</label>
                                <input type="text"
                                       name="cidade"
                                       id="cidade"
                                       class="form-control @error('cidade') is-invalid @enderror"
                                       value="{{ old('cidade', $distributor->cidade) }}">
                                @error('cidade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">UF</label>
                                <input type="text"
                                       name="estado"
                                       id="estado"
                                       class="form-control @error('estado') is-invalid @enderror"
                                       value="{{ old('estado', $distributor->estado) }}"
                                       maxlength="2">
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-haskell">
                                <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                            </button>
                            <a href="{{ route('distributor.dashboard') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informações</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">
                        <strong>Status do Cadastro:</strong><br>
                        @if($distributor->status === 'approved')
                            <span class="badge bg-success">Aprovado</span>
                        @elseif($distributor->status === 'pending')
                            <span class="badge bg-warning text-dark">Pendente</span>
                        @else
                            <span class="badge bg-danger">Rejeitado</span>
                        @endif
                    </p>
                    <p class="small text-muted">
                        <strong>Cadastrado em:</strong><br>
                        {{ $distributor->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="small text-muted mb-0">
                        <strong>Última atualização:</strong><br>
                        {{ $distributor->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Máscaras
        $('#phone').mask('(00) 0000-0000');
        $('#phone2').mask('(00) 0000-0000');
        $('#whatsapp').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
        $('#estado').mask('AA');

        // Busca CEP
        $('#cep').blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('#logradouro').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#estado').val(data.uf);
                        $('#numero').focus();
                    }
                });
            }
        });
    });
</script>
@endsection
