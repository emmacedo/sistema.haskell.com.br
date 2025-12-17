@extends('adminlte::page')

@section('title', 'Novo Produto')

@section('content_header')
    <h1>Novo Produto de Interesse</h1>
@stop

@section('content')
    <x-adminlte-card>
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-input
                        name="name"
                        label="Nome do Produto *"
                        placeholder="Ex: Shampoo, Condicionador, Máscara..."
                        value="{{ old('name') }}"
                        required
                    />
                </div>

                <div class="col-md-3">
                    <x-adminlte-input
                        name="order"
                        label="Ordem de Exibição"
                        type="number"
                        min="0"
                        placeholder="0"
                        value="{{ old('order', 0) }}"
                    />
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-switch mt-2">
                            <input type="checkbox"
                                   class="custom-control-input"
                                   id="active"
                                   name="active"
                                   value="1"
                                   {{ old('active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="active">Produto Ativo</label>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </form>
    </x-adminlte-card>
@stop
