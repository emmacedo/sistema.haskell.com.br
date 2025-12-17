@extends('adminlte::page')

@section('title', 'Novo Vendedor')

@section('content_header')
    <h1>Novo Vendedor</h1>
@stop

@section('content')
    <x-adminlte-card>
        <form action="{{ route('sellers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-select2
                        name="distributor_id"
                        label="Distribuidor"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-building"></i>
                            </div>
                        </x-slot>
                        <option value="">Selecione o distribuidor</option>
                        @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->trade_name }}
                            </option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-input
                        name="name"
                        label="Nome"
                        placeholder="Nome completo"
                        value="{{ old('name') }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-user"></i>
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
                        value="{{ old('email') }}"
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
                <div class="col-md-4">
                    <x-adminlte-input
                        name="phone"
                        label="Telefone"
                        placeholder="(00) 0000-0000"
                        value="{{ old('phone') }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-4">
                    <x-adminlte-input
                        name="whatsapp"
                        label="WhatsApp"
                        placeholder="(00) 00000-0000"
                        value="{{ old('whatsapp') }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="col-md-4">
                    <x-adminlte-input
                        name="position"
                        label="Cargo"
                        placeholder="Gerente de Vendas"
                        value="{{ old('position') }}"
                        enable-old-support
                    >
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </form>
    </x-adminlte-card>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        // Máscaras
        $('input[name="phone"]').mask('(00) 0000-0000');
        $('input[name="whatsapp"]').mask('(00) 00000-0000');

        // Select2
        $('select[name="distributor_id"]').select2({
            theme: 'bootstrap4',
            placeholder: 'Selecione o distribuidor'
        });
    });
</script>
@stop
