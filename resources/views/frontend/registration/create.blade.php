@extends('layouts.app')

@section('title', 'Cadastro de Distribuidor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="text-center mb-2">Cadastro de Distribuidor</h1>
                    <p class="text-center text-muted mb-5">
                        Preencha o formulário abaixo para se cadastrar como distribuidor
                    </p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Atenção!</strong> Corrija os erros abaixo:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('registration.store') }}" method="POST" id="registrationForm">
                        @csrf

                        <!-- Dados da Empresa -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-building"></i> Dados da Empresa</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Razão Social *</label>
                                        <input type="text"
                                               class="form-control @error('company_name') is-invalid @enderror"
                                               id="company_name"
                                               name="company_name"
                                               value="{{ old('company_name') }}"
                                               required>
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="trade_name" class="form-label">Nome Fantasia *</label>
                                        <input type="text"
                                               class="form-control @error('trade_name') is-invalid @enderror"
                                               id="trade_name"
                                               name="trade_name"
                                               value="{{ old('trade_name') }}"
                                               required>
                                        @error('trade_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cnpj" class="form-label">CNPJ *</label>
                                        <input type="text"
                                               class="form-control @error('cnpj') is-invalid @enderror"
                                               id="cnpj"
                                               name="cnpj"
                                               value="{{ old('cnpj') }}"
                                               placeholder="00.000.000/0000-00"
                                               maxlength="18"
                                               required>
                                        @error('cnpj')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               id="email"
                                               name="email"
                                               value="{{ old('email') }}"
                                               required>
                                        <small class="text-muted">Enviaremos um código de verificação para este email</small>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="phone" class="form-label">Telefone *</label>
                                        <input type="text"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="(00) 0000-0000"
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="phone2" class="form-label">Telefone 2</label>
                                        <input type="text"
                                               class="form-control @error('phone2') is-invalid @enderror"
                                               id="phone2"
                                               name="phone2"
                                               value="{{ old('phone2') }}"
                                               placeholder="(00) 0000-0000">
                                        @error('phone2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text"
                                               class="form-control @error('whatsapp') is-invalid @enderror"
                                               id="whatsapp"
                                               name="whatsapp"
                                               value="{{ old('whatsapp') }}"
                                               placeholder="(00) 00000-0000">
                                        @error('whatsapp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url"
                                               class="form-control @error('website') is-invalid @enderror"
                                               id="website"
                                               name="website"
                                               value="{{ old('website') }}"
                                               placeholder="https://">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="cep" class="form-label">CEP *</label>
                                        <input type="text"
                                               class="form-control @error('cep') is-invalid @enderror"
                                               id="cep"
                                               name="cep"
                                               value="{{ old('cep') }}"
                                               placeholder="00000-000"
                                               maxlength="9"
                                               required>
                                        <small class="text-muted">Digite o CEP para buscar o endereço</small>
                                        @error('cep')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-7 mb-3">
                                        <label for="logradouro" class="form-label">Logradouro *</label>
                                        <input type="text"
                                               class="form-control @error('logradouro') is-invalid @enderror"
                                               id="logradouro"
                                               name="logradouro"
                                               value="{{ old('logradouro') }}"
                                               placeholder="Rua, Avenida, etc."
                                               required>
                                        @error('logradouro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="numero" class="form-label">Número *</label>
                                        <input type="text"
                                               class="form-control @error('numero') is-invalid @enderror"
                                               id="numero"
                                               name="numero"
                                               value="{{ old('numero') }}"
                                               placeholder="Nº"
                                               required>
                                        @error('numero')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text"
                                               class="form-control @error('complemento') is-invalid @enderror"
                                               id="complemento"
                                               name="complemento"
                                               value="{{ old('complemento') }}"
                                               placeholder="Apartamento, Sala, etc.">
                                        @error('complemento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="bairro" class="form-label">Bairro *</label>
                                        <input type="text"
                                               class="form-control @error('bairro') is-invalid @enderror"
                                               id="bairro"
                                               name="bairro"
                                               value="{{ old('bairro') }}"
                                               placeholder="Bairro"
                                               required>
                                        @error('bairro')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="cidade" class="form-label">Cidade *</label>
                                        <input type="text"
                                               class="form-control @error('cidade') is-invalid @enderror"
                                               id="cidade"
                                               name="cidade"
                                               value="{{ old('cidade') }}"
                                               placeholder="Cidade"
                                               required>
                                        @error('cidade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-1 mb-3">
                                        <label for="estado" class="form-label">UF *</label>
                                        <input type="text"
                                               class="form-control @error('estado') is-invalid @enderror text-uppercase"
                                               id="estado"
                                               name="estado"
                                               value="{{ old('estado') }}"
                                               placeholder="UF"
                                               maxlength="2"
                                               required>
                                        @error('estado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vendedores -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Vendedores</h5>
                                <button type="button" class="btn btn-sm btn-light" id="add_seller">
                                    <i class="bi bi-plus-circle"></i> Adicionar Vendedor
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="sellers_container">
                                    @if(old('sellers'))
                                        @foreach(old('sellers') as $index => $seller)
                                            <div class="seller-item border rounded p-3 mb-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong>Vendedor {{ $index + 1 }}</strong>
                                                    @if($index > 0)
                                                        <button type="button" class="btn btn-sm btn-danger remove-seller">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Nome *</label>
                                                        <input type="text" class="form-control" name="sellers[{{ $index }}][name]" value="{{ $seller['name'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" class="form-control" name="sellers[{{ $index }}][email]" value="{{ $seller['email'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Telefone *</label>
                                                        <input type="text" class="form-control phone-mask" name="sellers[{{ $index }}][phone]" value="{{ $seller['phone'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">WhatsApp</label>
                                                        <input type="text" class="form-control phone-mask" name="sellers[{{ $index }}][whatsapp]" value="{{ $seller['whatsapp'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="seller-item border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <strong>Vendedor 1</strong>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Nome *</label>
                                                    <input type="text" class="form-control" name="sellers[0][name]" required>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Email *</label>
                                                    <input type="email" class="form-control" name="sellers[0][email]" required>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Telefone *</label>
                                                    <input type="text" class="form-control phone-mask" name="sellers[0][phone]" required>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">WhatsApp</label>
                                                    <input type="text" class="form-control phone-mask" name="sellers[0][whatsapp]">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <small class="text-muted">* Cadastre pelo menos 1 vendedor</small>
                                @error('sellers')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('search.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Cadastrar Distribuidor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let sellerIndex = {{ old('sellers') ? count(old('sellers')) : 1 }};

    // Máscara de CNPJ
    $('#cnpj').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 14) {
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }
        $(this).val(value);
    });

    // Máscara de CEP
    $('#cep').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }
        $(this).val(value);
    });

    // Máscara de UF (apenas letras maiúsculas)
    $('#estado').on('input', function() {
        let value = $(this).val().replace(/[^A-Za-z]/g, '').toUpperCase();
        $(this).val(value.substring(0, 2));
    });

    // Máscara de telefone
    function applyPhoneMask(element) {
        let value = $(element).val().replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        $(element).val(value);
    }

    $(document).on('input', '.phone-mask, #phone, #phone2, #whatsapp', function() {
        applyPhoneMask(this);
    });

    // Busca automática de endereço por CEP (ViaCEP)
    $('#cep').blur(function() {
        const cep = $(this).val().replace(/\D/g, '');

        if (cep.length !== 8) {
            return;
        }

        // Limpar campos
        $('#logradouro, #bairro, #cidade, #estado').val('...');

        // Desabilitar campos durante a busca
        $('#logradouro, #bairro, #cidade, #estado').prop('disabled', true);

        $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
            if (!data.erro) {
                $('#logradouro').val(data.logradouro);
                $('#bairro').val(data.bairro);
                $('#cidade').val(data.localidade);
                $('#estado').val(data.uf);
                $('#numero').focus();
            } else {
                alert('CEP não encontrado.');
                $('#logradouro, #bairro, #cidade, #estado').val('');
            }
        }).fail(function() {
            alert('Erro ao buscar CEP. Verifique sua conexão e tente novamente.');
            $('#logradouro, #bairro, #cidade, #estado').val('');
        }).always(function() {
            // Reabilitar campos
            $('#logradouro, #bairro, #cidade, #estado').prop('disabled', false);
        });
    });

    // Adicionar vendedor
    $('#add_seller').click(function() {
        const sellerHtml = `
            <div class="seller-item border rounded p-3 mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Vendedor ${sellerIndex + 1}</strong>
                    <button type="button" class="btn btn-sm btn-danger remove-seller">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nome *</label>
                        <input type="text" class="form-control" name="sellers[${sellerIndex}][name]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="sellers[${sellerIndex}][email]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Telefone *</label>
                        <input type="text" class="form-control phone-mask" name="sellers[${sellerIndex}][phone]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" class="form-control phone-mask" name="sellers[${sellerIndex}][whatsapp]">
                    </div>
                </div>
            </div>
        `;

        $('#sellers_container').append(sellerHtml);
        sellerIndex++;
    });

    // Remover vendedor
    $(document).on('click', '.remove-seller', function() {
        $(this).closest('.seller-item').remove();

        // Renumerar vendedores
        $('.seller-item').each(function(index) {
            $(this).find('strong').first().text(`Vendedor ${index + 1}`);
        });
    });
});
</script>

@endsection
