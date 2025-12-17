@extends('layouts.distributor')

@section('title', 'Editar Vendedor')
@section('page-title', 'Editar Vendedor')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Vendedor</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('distributor.sellers.update', $seller->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $seller->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-mail *</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $seller->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Telefone</label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $seller->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WhatsApp</label>
                                <input type="text"
                                       name="whatsapp"
                                       id="whatsapp"
                                       class="form-control @error('whatsapp') is-invalid @enderror"
                                       value="{{ old('whatsapp', $seller->whatsapp) }}">
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cargo / Função</label>
                            <input type="text"
                                   name="position"
                                   class="form-control @error('position') is-invalid @enderror"
                                   value="{{ old('position', $seller->position) }}">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-haskell">
                                <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                            </button>
                            <a href="{{ route('distributor.sellers') }}" class="btn btn-outline-secondary">
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
                        <strong>Cadastrado em:</strong><br>
                        {{ $seller->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="small text-muted mb-0">
                        <strong>Última atualização:</strong><br>
                        {{ $seller->updated_at->format('d/m/Y H:i') }}
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
        $('#whatsapp').mask('(00) 00000-0000');
    });
</script>
@endsection
