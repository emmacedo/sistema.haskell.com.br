@extends('adminlte::page')

@section('title', 'Dashboard - Haskell')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    {{-- Overlay de loading para feedback visual durante consultas lentas --}}
    <div id="loading-overlay">
        <div class="loading-box">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 mb-0 font-weight-bold">Carregando dados...</p>
        </div>
    </div>

    {{-- Filtro de Período --}}
    <div class="card card-outline card-primary mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                {{-- Botões de período pré-definido --}}
                <div class="d-flex align-items-center flex-wrap">
                    <span class="mr-2 font-weight-bold">Período:</span>
                    <a href="{{ route('admin.dashboard', ['period' => '5']) }}"
                       class="btn btn-sm {{ $period === '5' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">
                        5 dias
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => '15']) }}"
                       class="btn btn-sm {{ $period === '15' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">
                        15 dias
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => '30']) }}"
                       class="btn btn-sm {{ $period === '30' ? 'btn-primary' : 'btn-outline-primary' }} mr-1">
                        30 dias
                    </a>
                    <button type="button" id="btn-custom-period"
                            class="btn btn-sm {{ $period === 'custom' ? 'btn-primary' : 'btn-outline-primary' }} mr-3">
                        Personalizado
                    </button>

                    {{-- Campos de data personalizada (ocultos por padrão, visíveis se period=custom) --}}
                    <form id="custom-period-form" method="GET" action="{{ route('admin.dashboard') }}"
                          class="d-inline-flex align-items-center {{ $period === 'custom' ? '' : 'd-none' }}">
                        <input type="hidden" name="period" value="custom">
                        <label class="mb-0 mr-1">De:</label>
                        <input type="date" name="date_start" class="form-control form-control-sm mr-2"
                               value="{{ $dateStart->format('Y-m-d') }}" style="width: 150px;">
                        <label class="mb-0 mr-1">Até:</label>
                        <input type="date" name="date_end" class="form-control form-control-sm mr-2"
                               value="{{ $dateEnd->format('Y-m-d') }}" style="width: 150px;">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </form>
                </div>

                {{-- Indicador do período atual --}}
                <div class="text-muted text-sm">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $dateStart->format('d/m/Y') }} - {{ $dateEnd->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Cards de Estatísticas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDistributors }}</h3>
                    <p>Total Distribuidores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('distributors.index') }}" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $approvedDistributors }}</h3>
                    <p>Distribuidores Aprovados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('distributors.index', ['status' => 'approved']) }}" class="small-box-footer">
                    Ver aprovados <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingDistributors }}</h3>
                    <p>Pendentes de Aprovação</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('distributors.index', ['status' => 'pending']) }}" class="small-box-footer">
                    Ver pendentes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $unreadMessages }}</h3>
                    <p>Mensagens Não Lidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('contact-messages.index', ['filter' => 'unread']) }}" class="small-box-footer">
                    Ver mensagens <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Gráfico de Buscas --}}
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Buscas no Período" theme="primary" icon="fas fa-chart-line">
                <canvas id="searchesChart" style="height: 300px;"></canvas>
            </x-adminlte-card>
        </div>
    </div>

    {{-- Top Cidades e Oportunidades --}}
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Top 10 Cidades Mais Buscadas" theme="info" icon="fas fa-search">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cidade</th>
                            <th>UF</th>
                            <th>Buscas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSearchedCities as $index => $stat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $stat->city?->name ?? 'N/A' }}</td>
                                <td>{{ $stat->city?->state?->uf ?? '-' }}</td>
                                <td><span class="badge badge-primary">{{ $stat->search_count }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma busca registrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>

        <div class="col-md-6">
            <x-adminlte-card title="Oportunidades (Cidades Sem Cobertura)" theme="warning" icon="fas fa-exclamation-triangle">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cidade</th>
                            <th>UF</th>
                            <th>Buscas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citiesWithoutCoverage as $index => $stat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $stat->city?->name ?? 'N/A' }}</td>
                                <td>{{ $stat->city?->state?->uf ?? '-' }}</td>
                                <td><span class="badge badge-warning">{{ $stat->search_count }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Todas as cidades buscadas têm cobertura</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>
    </div>

    {{-- Distribuidores Pendentes e Mensagens Recentes --}}
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Distribuidores Pendentes Recentes" theme="warning" icon="fas fa-clock">
                @forelse($recentPendingDistributors as $distributor)
                    <div class="mb-3 pb-3 border-bottom">
                        <h5><a href="{{ route('distributors.show', $distributor->id) }}">{{ $distributor->trade_name }}</a></h5>
                        <p class="mb-1 text-sm">
                            <strong>CNPJ:</strong> {{ $distributor->cnpj }}<br>
                            <strong>Email:</strong> {{ $distributor->email }}<br>
                            <strong>Cidades:</strong> {{ $distributor->cities->count() }}<br>
                            <strong>Cadastrado:</strong> {{ $distributor->created_at->diffForHumans() }}
                        </p>
                        <form action="{{ route('distributors.approve', $distributor->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i> Aprovar
                            </button>
                        </form>
                        <a href="{{ route('distributors.edit', $distributor->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                @empty
                    <p class="text-center">Nenhum distribuidor pendente</p>
                @endforelse
            </x-adminlte-card>
        </div>

        <div class="col-md-6">
            <x-adminlte-card title="Mensagens Não Lidas Recentes" theme="danger" icon="fas fa-envelope">
                @forelse($recentUnreadMessages as $message)
                    <div class="mb-3 pb-3 border-bottom">
                        <h5><a href="{{ route('contact-messages.show', $message->id) }}">{{ $message->sender_name }}</a></h5>
                        <p class="mb-1 text-sm">
                            <strong>Email:</strong> {{ $message->sender_email }}<br>
                            <strong>Sobre:</strong> {{ $message->distributor?->trade_name ?? 'N/A' }}<br>
                            <strong>Cidade:</strong> {{ $message->city?->name ?? 'N/A' }} - {{ $message->city?->state?->uf ?? '' }}<br>
                            <strong>Recebida:</strong> {{ $message->created_at->diffForHumans() }}
                        </p>
                        <p class="text-muted">{{ Str::limit($message->message, 100) }}</p>
                        <a href="{{ route('contact-messages.show', $message->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Ver Mensagem
                        </a>
                    </div>
                @empty
                    <p class="text-center">Nenhuma mensagem não lida</p>
                @endforelse
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Overlay de loading exibido durante navegação dos filtros */
        #loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        #loading-overlay.active {
            display: flex;
        }
        #loading-overlay .loading-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        #loading-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Toggle dos campos de data personalizada
        document.getElementById('btn-custom-period').addEventListener('click', function() {
            const form = document.getElementById('custom-period-form');
            form.classList.toggle('d-none');
        });

        // Exibe overlay de loading ao clicar nos botões de período ou submeter o form
        const overlay = document.getElementById('loading-overlay');

        // Links de período pré-definido (5, 15, 30 dias)
        document.querySelectorAll('.card-primary a[href*="period="]').forEach(function(link) {
            link.addEventListener('click', function() {
                overlay.classList.add('active');
            });
        });

        // Submit do formulário de período personalizado
        document.getElementById('custom-period-form').addEventListener('submit', function() {
            overlay.classList.add('active');
        });
    </script>
    <script>
        // Gráfico de buscas
        const searchesData = @json($searchesInPeriod);
        const labels = searchesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
        });
        const data = searchesData.map(item => item.count);

        const ctx = document.getElementById('searchesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Buscas por Dia',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@stop
