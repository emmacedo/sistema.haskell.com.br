<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\ContactMessage;
use App\Models\SearchStatistic;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Resolver período do filtro (padrão: 15 dias)
        $period = $request->input('period', '15');

        if ($period === 'custom') {
            // Período personalizado: ler datas do request com fallback para 15 dias
            $dateStart = $request->filled('date_start')
                ? Carbon::parse($request->input('date_start'))->startOfDay()
                : now()->subDays(15)->startOfDay();

            $dateEnd = $request->filled('date_end')
                ? Carbon::parse($request->input('date_end'))->endOfDay()
                : now()->endOfDay();
        } else {
            // Período pré-definido: 5, 15 ou 30 dias
            $days = in_array($period, ['5', '15', '30']) ? (int) $period : 15;
            $period = (string) $days; // Normaliza valor inválido para '15'
            $dateStart = now()->subDays($days)->startOfDay();
            $dateEnd = now()->endOfDay();
        }

        // Validação: se dateStart > dateEnd, resetar para 15 dias
        if ($dateStart->greaterThan($dateEnd)) {
            $period = '15';
            $dateStart = now()->subDays(15)->startOfDay();
            $dateEnd = now()->endOfDay();
        }

        // Estatísticas filtradas por período
        $totalDistributors = Distributor::whereBetween('created_at', [$dateStart, $dateEnd])->count();
        $approvedDistributors = Distributor::where('status', 'approved')
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->count();
        $totalMessages = ContactMessage::whereBetween('created_at', [$dateStart, $dateEnd])->count();
        $totalSearches = SearchStatistic::whereBetween('created_at', [$dateStart, $dateEnd])->count();

        // Estatísticas de estado atual (NÃO filtradas por período)
        $pendingDistributors = Distributor::where('status', 'pending')->count();
        $unreadMessages = ContactMessage::unread()->count();
        $totalCities = City::count();

        // Gráfico de buscas no período selecionado
        $searchesInPeriod = SearchStatistic::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [$dateStart, $dateEnd])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top 10 cidades mais buscadas (filtradas por período)
        // Filtra registros com city_id nulo ou cidade inexistente para evitar erro de "property on null"
        $topSearchedCities = SearchStatistic::select('city_id', DB::raw('COUNT(*) as search_count'))
            ->whereNotNull('city_id')
            ->whereHas('city')
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->with('city.state')
            ->groupBy('city_id')
            ->orderByDesc('search_count')
            ->limit(10)
            ->get();

        // Top 10 cidades sem cobertura (filtradas por período)
        // whereNotNull e whereHas garantem que só retorna registros com cidade válida
        $citiesWithoutCoverage = SearchStatistic::select('city_id', DB::raw('COUNT(*) as search_count'))
            ->whereNotNull('city_id')
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->with('city.state')
            ->whereHas('city', function($query) {
                $query->doesntHave('distributors');
            })
            ->groupBy('city_id')
            ->orderByDesc('search_count')
            ->limit(10)
            ->get();

        // Distribuidores pendentes recentes (NÃO filtrados — listagem de ação)
        $recentPendingDistributors = Distributor::where('status', 'pending')
            ->with(['cities', 'sellers'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Mensagens não lidas recentes (NÃO filtradas — listagem de ação)
        $recentUnreadMessages = ContactMessage::unread()
            ->with(['distributor', 'city'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalDistributors',
            'approvedDistributors',
            'pendingDistributors',
            'totalMessages',
            'unreadMessages',
            'totalSearches',
            'totalCities',
            'searchesInPeriod',
            'topSearchedCities',
            'citiesWithoutCoverage',
            'recentPendingDistributors',
            'recentUnreadMessages',
            'period',
            'dateStart',
            'dateEnd'
        ));
    }
}
