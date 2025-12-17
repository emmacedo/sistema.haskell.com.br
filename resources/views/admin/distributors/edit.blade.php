@extends('adminlte::page')

@section('title', 'Editar Distribuidor')

@section('content_header')
    <h1>Editar Distribuidor</h1>
@stop

@section('content')
    <x-adminlte-card>
        <form action="{{ route('distributors.update', $distributor) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="distributor-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dados-tab" data-toggle="tab" href="#dados" role="tab">
                        <i class="fas fa-building"></i> Dados do Distribuidor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vendedores-tab" data-toggle="tab" href="#vendedores" role="tab">
                        <i class="fas fa-users"></i> Vendedores
                        <span class="badge badge-info">{{ $distributor->sellers->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cidades-tab" data-toggle="tab" href="#cidades" role="tab">
                        <i class="fas fa-map-marker-alt"></i> Cidades de Atendimento
                        <span class="badge badge-success">{{ $distributor->cities->count() }}</span>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-3">
                <!-- ABA 1: Dados do Distribuidor -->
                <div class="tab-pane fade show active" id="dados" role="tabpanel">
                    <div class="row">
                <div class="col-md-6">
                    <x-adminlte-input
                        name="company_name"
                        label="Razão Social"
                        placeholder="Razão Social"
                        value="{{ old('company_name', $distributor->company_name) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-building"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input
                        name="trade_name"
                        label="Nome Fantasia"
                        placeholder="Nome Fantasia"
                        value="{{ old('trade_name', $distributor->trade_name) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-store"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-input
                        name="cnpj"
                        label="CNPJ"
                        placeholder="00.000.000/0000-00"
                        value="{{ old('cnpj', $distributor->cnpj) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-id-card"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input
                        name="email"
                        label="E-mail"
                        type="email"
                        placeholder="email@exemplo.com"
                        value="{{ old('email', $distributor->email) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <x-adminlte-input
                        name="phone"
                        label="Telefone"
                        placeholder="(00) 0000-0000"
                        value="{{ old('phone', $distributor->phone) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input
                        name="phone2"
                        label="Telefone 2"
                        placeholder="(00) 0000-0000"
                        value="{{ old('phone2', $distributor->phone2) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input
                        name="whatsapp"
                        label="WhatsApp"
                        placeholder="(00) 00000-0000"
                        value="{{ old('whatsapp', $distributor->whatsapp) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input
                        name="website"
                        label="Website"
                        placeholder="https://exemplo.com"
                        value="{{ old('website', $distributor->website) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-globe"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <x-adminlte-input
                        name="cep"
                        label="CEP"
                        placeholder="00000-000"
                        value="{{ old('cep', $distributor->cep) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-map-pin"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-7">
                    <x-adminlte-input
                        name="logradouro"
                        label="Logradouro"
                        placeholder="Rua, Avenida, etc."
                        value="{{ old('logradouro', $distributor->logradouro) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-road"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-2">
                    <x-adminlte-input
                        name="numero"
                        label="Número"
                        placeholder="Nº"
                        value="{{ old('numero', $distributor->numero) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-input
                        name="complemento"
                        label="Complemento"
                        placeholder="Apartamento, Sala, etc."
                        value="{{ old('complemento', $distributor->complemento) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-4">
                    <x-adminlte-input
                        name="bairro"
                        label="Bairro"
                        placeholder="Bairro"
                        value="{{ old('bairro', $distributor->bairro) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input
                        name="cidade"
                        label="Cidade"
                        placeholder="Cidade"
                        value="{{ old('cidade', $distributor->cidade) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-city"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-1">
                    <x-adminlte-input
                        name="estado"
                        label="UF"
                        placeholder="UF"
                        value="{{ old('estado', $distributor->estado) }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-flag"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-select
                        name="status"
                        label="Status"
                        enable-old-support
                    >
                        <option value="pending" {{ old('status', $distributor->status) === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="approved" {{ old('status', $distributor->status) === 'approved' ? 'selected' : '' }}>Aprovado</option>
                        <option value="rejected" {{ old('status', $distributor->status) === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                    </x-adminlte-select>
                </div>
            </div>
                </div>
                <!-- FIM ABA 1: Dados do Distribuidor -->

                <!-- ABA 2: Vendedores -->
                <div class="tab-pane fade" id="vendedores" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="{{ route('sellers.create', ['distributor_id' => $distributor->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Adicionar Vendedor
                            </a>
                        </div>
                    </div>

                    @if($distributor->sellers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>WhatsApp</th>
                                        <th>Cargo</th>
                                        <th width="150">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($distributor->sellers as $seller)
                                        <tr>
                                            <td>{{ $seller->name }}</td>
                                            <td>{{ $seller->email }}</td>
                                            <td>{{ $seller->phone }}</td>
                                            <td>{{ $seller->whatsapp ?? '-' }}</td>
                                            <td>{{ $seller->position ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('sellers.edit', $seller) }}" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteSeller({{ $seller->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum vendedor cadastrado para este distribuidor.
                        </div>
                    @endif
                </div>
                <!-- FIM ABA 2: Vendedores -->

                <!-- ABA 3: Cidades de Atendimento -->
                <div class="tab-pane fade" id="cidades" role="tabpanel">
                    <div class="row">
                <div class="col-md-12">
                    <label>Cidades de Atendimento *</label>
                    <small class="form-text text-muted mb-2">
                        <i class="fas fa-info-circle"></i> Selecione um estado, depois clique nas cidades para adicionar à lista de selecionadas
                    </small>
                </div>
            </div>

            <div class="row">
                <!-- Lista de Estados -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h5 class="mb-0"><i class="fas fa-map"></i> Estados</h5>
                        </div>
                        <div class="card-body p-0">
                            <select id="state-list" class="form-control" size="10" style="border: none; border-radius: 0; height: 450px; overflow-y: auto;">
                                <option value="">Carregando...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Lista de Cidades do Estado -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0"><i class="fas fa-city"></i> Cidades</h5>
                        </div>
                        <div class="card-body p-0">
                            <select id="city-list" class="form-control" multiple size="10" style="border: none; border-radius: 0; height: 400px; overflow-y: auto;">
                                <option value="">Selecione um estado</option>
                            </select>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" id="add-cities-btn" class="btn btn-success btn-sm">
                                <i class="fas fa-arrow-right"></i> Adicionar Selecionadas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lista de Cidades Selecionadas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Cidades Selecionadas</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul id="selected-cities-list" class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                @if($distributor->cities->count() > 0)
                                    @foreach($distributor->cities as $city)
                                        <li class="list-group-item d-flex justify-content-between align-items-center" data-city-id="{{ $city->id }}">
                                            <span>{{ $city->name }} ({{ $city->state->uf }})</span>
                                            <button type="button" class="btn btn-danger btn-sm remove-city-btn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item text-muted text-center" id="no-cities-message">
                                        Nenhuma cidade selecionada
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs para armazenar as cidades selecionadas -->
                <div id="cities-hidden-inputs">
                    @foreach($distributor->cities as $city)
                        <input type="hidden" name="cities[]" value="{{ $city->id }}">
                    @endforeach
                </div>
                </div>
                <!-- FIM ABA 3: Cidades de Atendimento -->
            </div>
            <!-- FIM Tab content -->

            <!-- Botões de ação (fora das abas) -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="{{ route('distributors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </form>

        {{-- Formulários de delete dos vendedores (fora do form principal para evitar aninhamento) --}}
        @foreach($distributor->sellers as $seller)
            <form id="delete-seller-{{ $seller->id }}" action="{{ route('sellers.destroy', $seller) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Função para confirmar exclusão de vendedor
    function deleteSeller(id) {
        Swal.fire({
            title: 'Excluir Vendedor',
            text: 'Tem certeza que deseja excluir este vendedor?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-seller-' + id).submit();
            }
        });
    }

    $(document).ready(function() {
        // Máscaras
        $('input[name="cnpj"]').mask('00.000.000/0000-00');
        $('input[name="phone"]').mask('(00) 0000-0000');
        $('input[name="phone2"]').mask('(00) 0000-0000');
        $('input[name="whatsapp"]').mask('(00) 00000-0000');
        $('input[name="cep"]').mask('00000-000');
        $('input[name="estado"]').mask('AA');

        // Busca automática de endereço por CEP
        $('input[name="cep"]').blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('input[name="logradouro"]').val(data.logradouro);
                        $('input[name="bairro"]').val(data.bairro);
                        $('input[name="cidade"]').val(data.localidade);
                        $('input[name="estado"]').val(data.uf);
                        $('input[name="numero"]').focus();
                    }
                });
            }
        });

        // ========== Sistema de 3 Listas para Seleção de Cidades ==========

        let selectedCities = [];
        let citiesData = {}; // Cache de dados das cidades

        // Carregar cidades já selecionadas
        $('input[name="cities[]"]').each(function() {
            selectedCities.push(parseInt($(this).val()));
        });

        // Carregar estados
        function loadStates() {
            $.get('{{ route("admin.states.index") }}', function(states) {
                const $stateList = $('#state-list');
                $stateList.empty();
                $stateList.append('<option value="">Selecione um estado...</option>');

                states.forEach(function(state) {
                    $stateList.append(`<option value="${state.id}">${state.name} (${state.uf})</option>`);
                });
            });
        }

        // Carregar cidades de um estado
        function loadCitiesByState(stateId) {
            const $cityList = $('#city-list');
            $cityList.empty();
            $cityList.append('<option value="">Carregando...</option>');

            $.get(`{{ url('admin/cities/by-state') }}/${stateId}`, function(cities) {
                $cityList.empty();

                if (cities.length === 0) {
                    $cityList.append('<option value="">Nenhuma cidade encontrada</option>');
                    return;
                }

                cities.forEach(function(city) {
                    // Não mostrar cidades já selecionadas
                    if (!selectedCities.includes(city.id)) {
                        $cityList.append(`<option value="${city.id}">${city.name}</option>`);
                        citiesData[city.id] = city.name;
                    }
                });
            });
        }

        // Adicionar cidades selecionadas
        function addSelectedCities() {
            const $cityList = $('#city-list');
            const selectedOptions = $cityList.val();

            if (!selectedOptions || selectedOptions.length === 0) {
                alert('Selecione pelo menos uma cidade');
                return;
            }

            const stateUf = $('#state-list option:selected').text().match(/\(([^)]+)\)/)[1];

            selectedOptions.forEach(function(cityId) {
                cityId = parseInt(cityId);

                if (!selectedCities.includes(cityId)) {
                    selectedCities.push(cityId);

                    const cityName = citiesData[cityId];
                    addCityToSelectedList(cityId, cityName, stateUf);

                    // Remover da lista de cidades disponíveis
                    $(`#city-list option[value="${cityId}"]`).remove();
                }
            });

            updateCitiesInput();
            removeNoMagazineMessage();
        }

        // Adicionar cidade à lista de selecionadas
        function addCityToSelectedList(cityId, cityName, stateUf) {
            const $selectedList = $('#selected-cities-list');

            const cityItem = `
                <li class="list-group-item d-flex justify-content-between align-items-center" data-city-id="${cityId}">
                    <span>${cityName} (${stateUf})</span>
                    <button type="button" class="btn btn-danger btn-sm remove-city-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </li>
            `;

            $selectedList.append(cityItem);
        }

        // Remover cidade da lista de selecionadas
        function removeCity(cityId) {
            cityId = parseInt(cityId);
            const index = selectedCities.indexOf(cityId);

            if (index > -1) {
                selectedCities.splice(index, 1);
            }

            updateCitiesInput();

            // Se não houver mais cidades, mostrar mensagem
            if (selectedCities.length === 0) {
                $('#selected-cities-list').append(`
                    <li class="list-group-item text-muted text-center" id="no-cities-message">
                        Nenhuma cidade selecionada
                    </li>
                `);
            }

            // Recarregar cidades do estado atual para mostrar a cidade removida
            const selectedStateId = $('#state-list').val();
            if (selectedStateId) {
                loadCitiesByState(selectedStateId);
            }
        }

        // Atualizar campos hidden com as cidades selecionadas
        function updateCitiesInput() {
            const $container = $('#cities-hidden-inputs');
            $container.empty();

            selectedCities.forEach(function(cityId) {
                $container.append(`<input type="hidden" name="cities[]" value="${cityId}">`);
            });
        }

        // Remover mensagem "Nenhuma cidade selecionada"
        function removeNoMagazineMessage() {
            $('#no-cities-message').remove();
        }

        // Event Listeners
        $('#state-list').change(function() {
            const stateId = $(this).val();
            if (stateId) {
                loadCitiesByState(stateId);
            } else {
                $('#city-list').empty();
                $('#city-list').append('<option value="">Selecione um estado</option>');
            }
        });

        $('#add-cities-btn').click(function() {
            addSelectedCities();
        });

        $(document).on('click', '.remove-city-btn', function() {
            const $listItem = $(this).closest('li');
            const cityId = $listItem.data('city-id');

            $listItem.remove();
            removeCity(cityId);
        });

        // Inicializar
        loadStates();
    });
</script>
@stop
