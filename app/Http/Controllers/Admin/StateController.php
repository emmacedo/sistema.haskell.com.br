<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Retorna todos os estados
     */
    public function index()
    {
        $states = State::orderBy('name')->get(['id', 'name', 'uf']);

        return response()->json($states);
    }
}
