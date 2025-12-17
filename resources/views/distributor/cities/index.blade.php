@extends('layouts.distributor')

@section('title', 'Cidades Atendidas')
@section('page-title', 'Cidades Atendidas')

@section('content')
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Gerenciar Cidades Atendidas</h6>
            <span class="badge bg-light text-dark">{{ $distributor->cities->count() }} cidades</span>
        </div>
        <div class="card-body">
            <form action="{{ route('distributor.cities.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <small>
                        <i class="bi bi-info-circle me-2"></i>
                        Selecione um estado, clique nas cidades para adicionar à lista de selecionadas. As cidades selecionadas são onde você atende.
                    </small>
                </div>

                <div class="row">
                    <!-- Lista de Estados -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-secondary text-white py-2">
                                <small><i class="bi bi-map me-2"></i>Estados</small>
                            </div>
                            <div class="card-body p-0">
                                <select id="state-list" class="form-select" size="12" style="border: none; border-radius: 0;">
                                    <option value="">Carregando...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Cidades do Estado -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header py-2" style="background: var(--haskell-teal); color: #fff;">
                                <small><i class="bi bi-building me-2"></i>Cidades Disponíveis</small>
                            </div>
                            <div class="card-body p-0">
                                <select id="city-list" class="form-select" multiple size="10" style="border: none; border-radius: 0;">
                                    <option value="">Selecione um estado</option>
                                </select>
                            </div>
                            <div class="card-footer text-center py-2">
                                <button type="button" id="add-cities-btn" class="btn btn-sm btn-haskell">
                                    <i class="bi bi-arrow-right me-1"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Cidades Selecionadas -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-header py-2" style="background: var(--haskell-lime); color: #333;">
                                <small><i class="bi bi-check-circle me-2"></i>Cidades Selecionadas</small>
                            </div>
                            <div class="card-body p-0">
                                <ul id="selected-cities-list" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($distributor->cities as $city)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2" data-city-id="{{ $city->id }}">
                                            <small>{{ $city->name }} ({{ $city->state->uf }})</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-city-btn" style="padding: 0 6px;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted text-center py-3" id="no-cities-message">
                                            <small>Nenhuma cidade selecionada</small>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs para armazenar as cidades selecionadas -->
                <div id="cities-hidden-inputs">
                    @foreach($distributor->cities as $city)
                        <input type="hidden" name="cities[]" value="{{ $city->id }}">
                    @endforeach
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let selectedCities = [];
        let citiesData = {};

        // Carregar cidades já selecionadas
        $('input[name="cities[]"]').each(function() {
            selectedCities.push(parseInt($(this).val()));
        });

        // Carregar estados
        function loadStates() {
            $.get('{{ route("distributor.api.states") }}', function(states) {
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

            $.get(`{{ url('painel/api/cities') }}/${stateId}`, function(cities) {
                $cityList.empty();

                if (cities.length === 0) {
                    $cityList.append('<option value="">Nenhuma cidade</option>');
                    return;
                }

                cities.forEach(function(city) {
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

            const stateUf = $('#state-list option:selected').text().match(/\(([^)]+)\)/)?.[1] || '';

            selectedOptions.forEach(function(cityId) {
                cityId = parseInt(cityId);

                if (!selectedCities.includes(cityId)) {
                    selectedCities.push(cityId);

                    const cityName = citiesData[cityId];
                    addCityToSelectedList(cityId, cityName, stateUf);

                    $(`#city-list option[value="${cityId}"]`).remove();
                }
            });

            updateCitiesInput();
            removeNoMessage();
        }

        // Adicionar cidade à lista de selecionadas
        function addCityToSelectedList(cityId, cityName, stateUf) {
            const $selectedList = $('#selected-cities-list');

            const cityItem = `
                <li class="list-group-item d-flex justify-content-between align-items-center py-2" data-city-id="${cityId}">
                    <small>${cityName} (${stateUf})</small>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-city-btn" style="padding: 0 6px;">
                        <i class="bi bi-x"></i>
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

            if (selectedCities.length === 0) {
                $('#selected-cities-list').append(`
                    <li class="list-group-item text-muted text-center py-3" id="no-cities-message">
                        <small>Nenhuma cidade selecionada</small>
                    </li>
                `);
            }

            // Recarregar cidades do estado atual
            const selectedStateId = $('#state-list').val();
            if (selectedStateId) {
                loadCitiesByState(selectedStateId);
            }
        }

        // Atualizar campos hidden
        function updateCitiesInput() {
            const $container = $('#cities-hidden-inputs');
            $container.empty();

            selectedCities.forEach(function(cityId) {
                $container.append(`<input type="hidden" name="cities[]" value="${cityId}">`);
            });
        }

        // Remover mensagem "Nenhuma cidade selecionada"
        function removeNoMessage() {
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
@endsection
