<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistributorRequest;
use App\Http\Requests\UpdateDistributorRequest;
use App\Models\City;
use App\Models\Distributor;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributorController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Distributor::with(['cities', 'sellers']);

        // Filtro de status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('trade_name', 'like', "%{$search}%")
                    ->orWhere('cnpj', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $distributors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.distributors.index', compact('distributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Não carregamos as cidades aqui, serão buscadas via AJAX
        return view('admin.distributors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistributorRequest $request)
    {
        try {
            DB::beginTransaction();

            $distributor = Distributor::create($request->except('cities'));

            // Sincronizar cidades
            $distributor->cities()->sync($request->cities);

            DB::commit();

            return redirect()
                ->route('distributors.index')
                ->with('success', 'Distribuidor criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar distribuidor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        $distributor->load(['cities.state', 'sellers', 'contactMessages']);

        return view('admin.distributors.show', compact('distributor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distributor $distributor)
    {
        $distributor->load(['cities.state']);

        return view('admin.distributors.edit', compact('distributor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributorRequest $request, Distributor $distributor)
    {
        try {
            DB::beginTransaction();

            $distributor->update($request->except('cities'));

            // Sincronizar cidades
            $distributor->cities()->sync($request->cities);

            DB::commit();

            return redirect()
                ->route('distributors.index')
                ->with('success', 'Distribuidor atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar distribuidor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distributor $distributor)
    {
        try {
            $distributor->delete();

            return redirect()
                ->route('distributors.index')
                ->with('success', 'Distribuidor excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir distribuidor: ' . $e->getMessage());
        }
    }

    /**
     * Approve distributor.
     */
    public function approve(Distributor $distributor)
    {
        try {
            $distributor->update([
                'status' => 'approved',
            ]);

            // Enviar email de aprovação
            $this->emailService->sendWelcomeEmail($distributor);

            return redirect()
                ->back()
                ->with('success', 'Distribuidor aprovado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao aprovar distribuidor: ' . $e->getMessage());
        }
    }

    /**
     * Reject distributor.
     */
    public function reject(Request $request, Distributor $distributor)
    {
        try {
            $distributor->update([
                'status' => 'rejected',
            ]);

            // Enviar email de rejeição
            $this->emailService->sendRejectionEmail($distributor);

            return redirect()
                ->back()
                ->with('success', 'Distribuidor rejeitado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao rejeitar distribuidor: ' . $e->getMessage());
        }
    }
}
