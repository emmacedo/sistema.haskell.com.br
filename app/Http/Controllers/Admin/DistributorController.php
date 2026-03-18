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

            // Sincronizar cidades com apropriação automática (se informadas)
            $appropriatedMessage = null;
            if ($request->has('cities')) {
                $appropriatedMessage = $this->syncCitiesWithAppropriation($distributor, $request->cities);
            }

            DB::commit();

            // Montar mensagem de sucesso com aviso de apropriação, se houver
            $successMessage = 'Distribuidor criado com sucesso!';
            if ($appropriatedMessage) {
                $successMessage .= ' ' . $appropriatedMessage;
            }

            return redirect()
                ->route('distributors.index')
                ->with('success', $successMessage);
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

            // Sincronizar cidades com apropriação automática
            $appropriatedMessage = $this->syncCitiesWithAppropriation($distributor, $request->cities ?? []);

            DB::commit();

            // Montar mensagem de sucesso com aviso de apropriação, se houver
            $successMessage = 'Distribuidor atualizado com sucesso!';
            if ($appropriatedMessage) {
                $successMessage .= ' ' . $appropriatedMessage;
            }

            return redirect()
                ->route('distributors.index')
                ->with('success', $successMessage);
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

    /**
     * Sincroniza cidades com apropriação automática.
     *
     * Uma cidade só pode pertencer a um distribuidor por vez. Se alguma cidade
     * informada já estiver vinculada a outro distribuidor, ela é automaticamente
     * removida do anterior e vinculada ao atual (última alteração tem prioridade).
     *
     * @param  Distributor  $distributor  O distribuidor que receberá as cidades
     * @param  array        $cityIds      IDs das cidades a vincular
     * @return string|null  Mensagem de aviso sobre cidades apropriadas, ou null
     */
    private function syncCitiesWithAppropriation(Distributor $distributor, array $cityIds): ?string
    {
        if (empty($cityIds)) {
            // Remove todas as cidades do distribuidor se nenhuma foi informada
            $distributor->cities()->sync([]);
            return null;
        }

        // Buscar cidades que já estão vinculadas a OUTROS distribuidores
        $appropriatedCities = DB::table('city_distributor')
            ->join('cities', 'cities.id', '=', 'city_distributor.city_id')
            ->whereIn('city_distributor.city_id', $cityIds)
            ->where('city_distributor.distributor_id', '!=', $distributor->id)
            ->pluck('cities.name');

        // Remover essas cidades dos outros distribuidores antes de vincular
        if ($appropriatedCities->isNotEmpty()) {
            DB::table('city_distributor')
                ->whereIn('city_id', $cityIds)
                ->where('distributor_id', '!=', $distributor->id)
                ->delete();
        }

        // Sincronizar as cidades no distribuidor atual
        $distributor->cities()->sync($cityIds);

        // Retornar mensagem de aviso se houve apropriação
        if ($appropriatedCities->isNotEmpty()) {
            $count = $appropriatedCities->count();
            $cityNames = $appropriatedCities->implode(', ');

            return "Atenção: {$count} cidade(s) foram transferidas de outros distribuidores: {$cityNames}.";
        }

        return null;
    }
}
