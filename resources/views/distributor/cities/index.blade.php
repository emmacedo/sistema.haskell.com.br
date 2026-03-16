@extends('layouts.distributor')

@section('title', 'Cidades Atendidas')
@section('page-title', 'Cidades Atendidas')

@section('content')
    <div class="table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Cidades Atendidas</h6>
            <span class="badge bg-light text-dark">{{ $cities->count() }} {{ $cities->count() === 1 ? 'cidade' : 'cidades' }}</span>
        </div>
        <div class="card-body">
            {{-- Informativo: somente o administrador pode alterar as cidades --}}
            <div class="alert alert-info mb-4">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    As cidades de atendimento são gerenciadas pelo administrador. Caso precise alterar, entre em contato com a administração.
                </small>
            </div>

            @if($cities->count() > 0)
                {{-- Agrupar cidades por estado para melhor visualização --}}
                @foreach($citiesByState as $uf => $group)
                    <div class="mb-3">
                        <h6 class="fw-bold text-muted mb-2">
                            <i class="bi bi-geo me-1"></i>{{ $group['state_name'] }} ({{ $uf }})
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($group['cities'] as $city)
                                <span class="badge rounded-pill" style="background: var(--haskell-teal); font-size: 0.85rem; padding: 6px 12px;">
                                    {{ $city->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-geo-alt" style="font-size: 2rem;"></i>
                    <p class="mt-2">Nenhuma cidade de atendimento cadastrada.</p>
                    <small>Entre em contato com o administrador para vincular suas cidades.</small>
                </div>
            @endif
        </div>
    </div>
@endsection
