<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Distributor;
use App\Models\SearchStatistic;
use App\Models\State;
use App\Services\CepService;
use Illuminate\Http\Request;

class DistributorSearchController extends Controller
{
    protected $cepService;

    public function __construct(CepService $cepService)
    {
        $this->cepService = $cepService;
    }

    /**
     * Exibe a página de busca
     */
    public function index()
    {
        return view('frontend.search.index');
    }

    /**
     * Busca distribuidores por CEP, cidade ou estado (detecção automática)
     */
    public function search(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string',
        ]);

        $searchTerm = trim($request->input('search_term'));
        $searchType = $request->input('search_type', 'auto');
        $cityId = $request->input('city_id'); // ID da cidade selecionada no autocomplete

        $city = null;
        $cities = collect();
        $distributors = collect();

        // Se veio um city_id do autocomplete, usar diretamente
        if ($cityId) {
            $city = City::find($cityId);
            $searchType = 'city';
        } else {
            // Detectar automaticamente o tipo de busca
            $searchType = $this->detectSearchType($searchTerm);

            switch ($searchType) {
                case 'cep':
                    // Buscar por CEP via ViaCEP
                    $cepData = $this->cepService->buscar($searchTerm);
                    if ($cepData && isset($cepData['ibge'])) {
                        $city = City::where('ibge_code', $cepData['ibge'])->first();
                    }
                    break;

                case 'state':
                    // Buscar por UF - retorna todas as cidades do estado com distribuidores
                    $state = State::where('uf', strtoupper($searchTerm))->first();
                    if ($state) {
                        // Buscar distribuidores que atendem cidades deste estado
                        $distributors = Distributor::whereHas('cities', function ($query) use ($state) {
                                $query->where('state_id', $state->id);
                            })
                            ->where('status', 'approved')
                            ->with(['sellers', 'cities.state'])
                            ->get();

                        // Registrar estatística
                        $this->registerStatistic('state', $searchTerm, null, $distributors->count(), $request);

                        return view('frontend.search.results', [
                            'city' => null,
                            'state' => $state,
                            'distributors' => $distributors,
                            'searchTerm' => $searchTerm,
                            'searchType' => 'state',
                        ]);
                    }
                    break;

                case 'city':
                default:
                    // Buscar por nome da cidade
                    $city = City::where('name', 'LIKE', "%{$searchTerm}%")->first();
                    break;
            }
        }

        // Buscar distribuidores da cidade encontrada
        if ($city) {
            $distributors = Distributor::whereHas('cities', function ($query) use ($city) {
                    $query->where('cities.id', $city->id);
                })
                ->where('status', 'approved')
                ->with(['sellers', 'cities'])
                ->get();
        }

        // Registrar estatística
        $this->registerStatistic($searchType, $searchTerm, $city, $distributors->count(), $request);

        return view('frontend.search.results', [
            'city' => $city,
            'state' => null,
            'distributors' => $distributors,
            'searchTerm' => $searchTerm,
            'searchType' => $searchType,
        ]);
    }

    /**
     * Detecta automaticamente o tipo de busca baseado no termo
     * - CEP: 8 dígitos numéricos (com ou sem hífen)
     * - Estado: 2 letras (UF)
     * - Cidade: qualquer outro texto
     */
    private function detectSearchType(string $term): string
    {
        // Remover espaços
        $term = trim($term);

        // CEP: 8 dígitos (formato: 00000-000 ou 00000000)
        $cepClean = preg_replace('/[^0-9]/', '', $term);
        if (strlen($cepClean) === 8) {
            return 'cep';
        }

        // Estado: exatamente 2 letras (UF)
        if (preg_match('/^[a-zA-Z]{2}$/', $term)) {
            // Verificar se é uma UF válida
            $state = State::where('uf', strtoupper($term))->first();
            if ($state) {
                return 'state';
            }
        }

        // Padrão: busca por cidade
        return 'city';
    }

    /**
     * Autocomplete para busca de cidades
     */
    public function autocomplete(Request $request)
    {
        $term = $request->input('term', '');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $cities = City::where('name', 'LIKE', "%{$term}%")
            ->with('state')
            ->limit(20)
            ->get()
            ->map(function ($city) {
                return [
                    'id' => $city->id,
                    'value' => $city->name,
                    'label' => "{$city->name} - {$city->state->uf}",
                ];
            });

        return response()->json($cities);
    }

    /**
     * Registra estatística de busca
     */
    private function registerStatistic($searchType, $searchTerm, $city, $resultsCount, Request $request)
    {
        SearchStatistic::create([
            'city_id' => $city?->id,
            'search_term' => $searchTerm,
            'search_type' => $searchType,
            'has_result' => $resultsCount > 0,
            'results_count' => $resultsCount,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
