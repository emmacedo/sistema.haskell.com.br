@extends('layouts.app')

@section('title', 'Resultados da Busca')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Campo de busca no topo -->
            <div class="mt-4">
                <form action="{{ route('search.search') }}" method="POST" id="searchForm">
                    @csrf
                    <input type="hidden" name="search_type" id="search_type" value="auto">

                    <div class="search-box">
                        <input type="text"
                               name="search_term"
                               id="search_input"
                               placeholder="busque por cep, cidade ou estado"
                               value="{{ $searchTerm }}"
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

            <!-- Container de resultados (será ocultado quando mostrar formulário) -->
            <div class="mt-5" id="resultsContainer">
                @if($distributors->isEmpty())
                    <!-- Nenhum resultado encontrado -->
                    <div class="no-results">
                        <h4>Nenhum distribuidor encontrado</h4>
                        <p>
                            @if(isset($city) && $city)
                                Ainda não temos distribuidores cadastrados em {{ $city->name }} - {{ $city->state->uf }}.
                            @elseif(isset($state) && $state)
                                Ainda não temos distribuidores cadastrados em {{ $state->name }}.
                            @else
                                Não encontramos distribuidores para esta localização.
                            @endif
                        </p>
                    </div>
                @else
                    <!-- Lista de distribuidores -->
                    @foreach($distributors as $distributor)
                        <div class="distributor-card">
                            <!-- Header com nome do distribuidor -->
                            <div class="distributor-card-header">
                                <h3>{{ $distributor->trade_name }}</h3>
                            </div>

                            <!-- Body com vendedores -->
                            <div class="distributor-card-body">
                                @if($distributor->sellers->isNotEmpty())
                                    @foreach($distributor->sellers as $seller)
                                        <div class="seller-section">
                                            <div class="seller-name">{{ $seller->name }}</div>

                                            <!-- Telefone/WhatsApp do vendedor -->
                                            @if($seller->phone || $seller->whatsapp)
                                                <div class="contact-row">
                                                    <div class="contact-field">
                                                        {{ $seller->phone ?: $seller->whatsapp }}
                                                    </div>
                                                    @php
                                                        $whatsappNumber = $seller->whatsapp ?: $seller->phone;
                                                        $whatsappClean = preg_replace('/[^0-9]/', '', $whatsappNumber);
                                                    @endphp
                                                    <a href="https://wa.me/55{{ $whatsappClean }}"
                                                       target="_blank"
                                                       class="contact-btn"
                                                       title="Conversar no WhatsApp">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </a>
                                                </div>
                                            @endif

                                            <!-- Email do vendedor - abre formulário -->
                                            @if($seller->email)
                                                <div class="contact-row">
                                                    <div class="contact-field">
                                                        {{ $seller->email }}
                                                    </div>
                                                    <button type="button"
                                                            class="contact-btn btn-open-contact"
                                                            data-seller-id="{{ $seller->id }}"
                                                            data-seller-name="{{ $seller->name }}"
                                                            data-distributor-name="{{ $distributor->trade_name }}"
                                                            title="Enviar mensagem">
                                                        <i class="bi bi-envelope"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Se não tem vendedores, mostrar contato do distribuidor -->
                                    <div class="seller-section">
                                        <div class="seller-name">Contato</div>

                                        @if($distributor->phone || $distributor->whatsapp)
                                            <div class="contact-row">
                                                <div class="contact-field">
                                                    {{ $distributor->phone ?: $distributor->whatsapp }}
                                                </div>
                                                @php
                                                    $whatsappNumber = $distributor->whatsapp ?: $distributor->phone;
                                                    $whatsappClean = preg_replace('/[^0-9]/', '', $whatsappNumber);
                                                @endphp
                                                <a href="https://wa.me/55{{ $whatsappClean }}"
                                                   target="_blank"
                                                   class="contact-btn"
                                                   title="Conversar no WhatsApp">
                                                    <i class="bi bi-whatsapp"></i>
                                                </a>
                                            </div>
                                        @endif

                                        @if($distributor->email)
                                            <div class="contact-row">
                                                <div class="contact-field">
                                                    {{ $distributor->email }}
                                                </div>
                                                <a href="mailto:{{ $distributor->email }}"
                                                   class="contact-btn"
                                                   title="Enviar e-mail">
                                                    <i class="bi bi-envelope"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Formulário de Contato (oculto inicialmente) -->
            <div class="mt-5" id="contactFormContainer" style="display: none;">
                <div class="distributor-card">
                    <div class="distributor-card-header">
                        <h3>FALE COM O DISTRIBUIDOR</h3>
                    </div>
                    <div class="distributor-card-body">
                        <!-- Mensagem de erro -->
                        <div id="contactError" class="message-box message-error" style="display: none;"></div>

                        <form id="contactForm">
                            @csrf
                            <input type="hidden" name="seller_id" id="contact_seller_id">

                            <div class="form-field">
                                <input type="text" name="sender_name" id="sender_name" placeholder="nome" required>
                            </div>

                            <div class="form-field">
                                <input type="email" name="sender_email" id="sender_email" placeholder="e-mail" required>
                            </div>

                            <div class="form-field">
                                <input type="text" name="sender_phone" id="sender_phone" placeholder="telefone" required>
                            </div>

                            <div class="form-field">
                                <input type="text" name="sender_city" id="sender_city" placeholder="cidade" required>
                            </div>

                            <div class="form-field">
                                <input type="text" name="sender_state" id="sender_state" placeholder="estado" maxlength="2" required>
                            </div>

                            <div class="form-field">
                                <select name="product_id" id="product_id">
                                    <option value="">produto de interesse</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <textarea name="message" id="message" placeholder="mensagem" rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn-submit" id="btnSubmit">
                                ENVIAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mensagem de Sucesso (oculta inicialmente) -->
            <div class="mt-5" id="successContainer" style="display: none;">
                <div class="distributor-card">
                    <div class="distributor-card-header">
                        <h3>MENSAGEM ENVIADA</h3>
                    </div>
                    <div class="distributor-card-body">
                        <div class="success-content">
                            <div class="success-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <p class="success-text">Sua mensagem foi enviada com sucesso!</p>
                            <p class="success-subtext">O vendedor entrará em contato em breve.</p>
                            <button type="button" class="btn-submit" id="btnBackToResults">
                                VOLTAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Formulário de contato */
    .form-field {
        margin-bottom: 12px;
    }

    .form-field input,
    .form-field select,
    .form-field textarea {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-radius: 8px;
        border-left: 4px solid var(--cor-header-card);
        font-size: 14px;
        background: #fff;
        color: #555;
    }

    .form-field input::placeholder,
    .form-field textarea::placeholder {
        color: #999;
    }

    .form-field select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%233d5a47' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-color: #fff;
    }

    .form-field textarea {
        resize: vertical;
        min-height: 120px;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background: var(--cor-header-card);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background: #2d4435;
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Mensagens de erro */
    .message-box {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .message-error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    /* Tela de sucesso */
    .success-content {
        text-align: center;
        padding: 20px 0;
    }

    .success-icon {
        font-size: 60px;
        color: var(--cor-header-card);
        margin-bottom: 20px;
    }

    .success-text {
        font-size: 18px;
        font-weight: bold;
        color: var(--cor-header-card);
        margin-bottom: 10px;
    }

    .success-subtext {
        font-size: 14px;
        color: #666;
        margin-bottom: 25px;
    }
</style>
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

    // Máscara de telefone
    $('#sender_phone').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 6) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
        } else if (value.length > 2) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
        } else if (value.length > 0) {
            value = '(' + value;
        }

        $(this).val(value);
    });

    // Estado em maiúsculas
    $('#sender_state').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });

    // Carregar produtos
    var productsLoaded = false;

    function loadProducts() {
        if (productsLoaded) return;

        $.ajax({
            url: "{{ route('contact.products') }}",
            method: 'GET',
            success: function(products) {
                var select = $('#product_id');
                select.empty();
                select.append('<option value="">produto de interesse</option>');

                products.forEach(function(product) {
                    select.append('<option value="' + product.id + '">' + product.name + '</option>');
                });

                productsLoaded = true;
            }
        });
    }

    // Abrir formulário de contato (substituir resultados)
    $('.btn-open-contact').on('click', function() {
        var sellerId = $(this).data('seller-id');

        $('#contact_seller_id').val(sellerId);
        $('#resultsContainer').hide();
        $('#contactFormContainer').show();
        loadProducts();

        // Scroll para o topo do formulário
        $('html, body').animate({
            scrollTop: $('#contactFormContainer').offset().top - 20
        }, 300);
    });

    // Enviar formulário de contato
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var btn = $('#btnSubmit');
        var originalText = btn.text();
        var errorBox = $('#contactError');

        // Ocultar erro anterior
        errorBox.hide();

        btn.prop('disabled', true).text('ENVIANDO...');

        $.ajax({
            url: "{{ route('contact.store') }}",
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    form[0].reset();
                    // Mostrar tela de sucesso
                    $('#contactFormContainer').hide();
                    $('#successContainer').show();

                    // Scroll para o topo
                    $('html, body').animate({
                        scrollTop: $('#successContainer').offset().top - 20
                    }, 300);
                } else {
                    // Mostrar erro
                    errorBox.text(response.message || 'Erro ao enviar mensagem.').show();
                }
            },
            error: function(xhr) {
                var msg = 'Erro ao enviar mensagem.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = Object.values(xhr.responseJSON.errors);
                    msg = errors[0][0];
                }
                // Mostrar erro
                errorBox.text(msg).show();
            },
            complete: function() {
                btn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Botão voltar da tela de sucesso
    $('#btnBackToResults').on('click', function() {
        $('#successContainer').hide();
        $('#resultsContainer').show();

        // Scroll para o topo
        $('html, body').animate({
            scrollTop: $('#resultsContainer').offset().top - 20
        }, 300);
    });
});
</script>
@endsection
