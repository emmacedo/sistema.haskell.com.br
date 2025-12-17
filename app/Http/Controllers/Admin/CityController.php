<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Busca cidades para Select2 AJAX
     */
    public function search(Request $request)
    {
        $term = $request->get('q');

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $cities = City::with('state')
            ->where(function($query) use ($term) {
                $query->where('name', 'LIKE', "%{$term}%")
                      ->orWhereHas('state', function($q) use ($term) {
                          $q->where('uf', 'LIKE', "%{$term}%")
                            ->orWhere('name', 'LIKE', "%{$term}%");
                      });
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        return response()->json($cities);
    }

    /**
     * Retorna cidades de um estado específico
     */
    public function byState(Request $request, $stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
