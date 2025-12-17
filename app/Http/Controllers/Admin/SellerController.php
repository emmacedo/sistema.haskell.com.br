<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Distributor;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Seller::with('distributor');

        // Filtro por distribuidor
        if ($request->filled('distributor_id')) {
            $query->where('distributor_id', $request->distributor_id);
        }

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sellers = $query->orderBy('created_at', 'desc')->paginate(15);
        $distributors = Distributor::orderBy('trade_name')->get();

        return view('admin.sellers.index', compact('sellers', 'distributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $distributors = Distributor::orderBy('trade_name')->get();

        return view('admin.sellers.create', compact('distributors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerRequest $request)
    {
        try {
            Seller::create($request->validated());

            return redirect()
                ->route('admin.sellers.index')
                ->with('success', 'Vendedor criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        $seller->load('distributor');

        return view('admin.sellers.show', compact('seller'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        $distributors = Distributor::orderBy('trade_name')->get();

        return view('admin.sellers.edit', compact('seller', 'distributors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        try {
            $seller->update($request->validated());

            return redirect()
                ->route('admin.sellers.index')
                ->with('success', 'Vendedor atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        try {
            $seller->delete();

            return redirect()
                ->route('admin.sellers.index')
                ->with('success', 'Vendedor excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir vendedor: ' . $e->getMessage());
        }
    }
}
