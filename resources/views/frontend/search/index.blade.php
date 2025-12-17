@extends('layouts.app')

@section('title', 'Encontre um Distribuidor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Formulário de busca unificado -->
            <div class="mt-5 pt-5">
                <form action="{{ route('search.search') }}" method="POST" id="searchForm">
                    @csrf
                    <input type="hidden" name="search_type" id="search_type" value="auto">

                    <div class="search-box">
                        <input type="text"
                               name="search_term"
                               id="search_input"
                               placeholder="busque por cep, cidade ou estado"
                               autocomplete="off"
                               required>
                        <button type="submit">
                            <svg class="search-icon" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Container para resultados (exibidos via AJAX ou após submit) -->
            <div id="results-container" class="mt-5">
                @yield('results')
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var searchInput = $('#search_input');

    // Detectar automaticamente o tipo de busca
    searchInput.on('input', function() {
        var value = $(this).val().replace(/\D/g, '');

        // Se tiver 8 dígitos numéricos, é um CEP - aplicar máscara
        if (value.length >= 5 && $(this).val().match(/^\d/)) {
            var formatted = value.slice(0, 5);
            if (value.length > 5) {
                formatted += '-' + value.slice(5, 8);
            }
            $(this).val(formatted);
            $('#search_type').val('cep');
        } else {
            $('#search_type').val('city');
        }
    });

    // Autocomplete para cidades
    searchInput.autocomplete({
        source: function(request, response) {
            // Não buscar autocomplete se parecer ser CEP
            if (request.term.match(/^\d/)) {
                response([]);
                return;
            }

            $.ajax({
                url: "{{ route('search.autocomplete') }}",
                data: { term: request.term },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            searchInput.val(ui.item.label);
            $('#search_type').val('city');

            // Criar campo hidden com o ID da cidade
            if ($('#city_id').length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'city_id',
                    name: 'city_id',
                    value: ui.item.id
                }).appendTo('#searchForm');
            } else {
                $('#city_id').val(ui.item.id);
            }

            return false;
        }
    });
});
</script>
@endsection
