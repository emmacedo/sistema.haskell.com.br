<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\ContactMessage;
use App\Models\SearchStatistic;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalDistributors = Distributor::count();
        $approvedDistributors = Distributor::where('status', 'approved')->count();
        $pendingDistributors = Distributor::where('status', 'pending')->count();
        $totalMessages = ContactMessage::count();
        $unreadMessages = ContactMessage::unread()->count();
        $totalSearches = SearchStatistic::count();
        $totalCities = City::count();

        // Gráfico de buscas dos últimos 30 dias
        $searchesLast30Days = SearchStatistic::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Top 10 cidades mais buscadas
        $topSearchedCities = SearchStatistic::select('city_id', DB::raw('COUNT(*) as search_count'))
            ->with('city')
            ->groupBy('city_id')
            ->orderByDesc('search_count')
            ->limit(10)
            ->get();

        // Top 10 cidades sem cobertura (mais buscadas sem distribuidor)
        $citiesWithoutCoverage = SearchStatistic::select('city_id', DB::raw('COUNT(*) as search_count'))
            ->with('city')
            ->whereHas('city', function($query) {
                $query->doesntHave('distributors');
            })
            ->groupBy('city_id')
            ->orderByDesc('search_count')
            ->limit(10)
            ->get();

        // Distribuidores pendentes recentes
        $recentPendingDistributors = Distributor::where('status', 'pending')
            ->with(['cities', 'sellers'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Mensagens não lidas recentes
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
            'searchesLast30Days',
            'topSearchedCities',
            'citiesWithoutCoverage',
            'recentPendingDistributors',
            'recentUnreadMessages'
        ));
    }
}
