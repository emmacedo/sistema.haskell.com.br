<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\Distributor;
use App\Models\Seller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller principal do painel do distribuidor
 * Gerencia todas as funcionalidades da área restrita
 */
class PanelController extends Controller
{
    /**
     * Retorna o distribuidor logado a partir da session
     */
    protected function getDistributor()
    {
        $distributorId = session('distributor_id');
        return Distributor::with(['cities', 'sellers'])->find($distributorId);
    }

    /**
     * Dashboard - Página inicial do painel
     */
    public function index()
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login')
                ->with('error', 'Sessão expirada. Faça login novamente.');
        }

        // Estatísticas para o dashboard
        $stats = [
            'total_cities' => $distributor->cities->count(),
            'total_sellers' => $distributor->sellers->count(),
            'total_messages' => ContactMessage::where('distributor_id', $distributor->id)->count(),
            'unread_messages' => ContactMessage::where('distributor_id', $distributor->id)
                ->whereNull('read_at')->count(),
        ];

        // Últimas mensagens recebidas
        $recentMessages = ContactMessage::where('distributor_id', $distributor->id)
            ->with(['seller', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('distributor.dashboard', compact('distributor', 'stats', 'recentMessages'));
    }

    /**
     * Exibe formulário de edição dos dados da empresa
     */
    public function editProfile()
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        return view('distributor.profile.edit', compact('distributor'));
    }

    /**
     * Atualiza os dados da empresa
     */
    public function updateProfile(Request $request)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $validated = $request->validate([
            'trade_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
        ], [
            'trade_name.required' => 'O nome fantasia é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
        ]);

        $distributor->update($validated);

        return redirect()->route('distributor.profile.edit')
            ->with('success', 'Dados atualizados com sucesso!');
    }

    /**
     * Exibe página de gerenciamento de cidades
     */
    public function cities()
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $distributor->load('cities.state');

        return view('distributor.cities.index', compact('distributor'));
    }

    /**
     * Atualiza as cidades atendidas
     */
    public function updateCities(Request $request)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $validated = $request->validate([
            'cities' => 'required|array|min:1',
            'cities.*' => 'exists:cities,id',
        ], [
            'cities.required' => 'Selecione pelo menos uma cidade.',
            'cities.min' => 'Selecione pelo menos uma cidade.',
        ]);

        $distributor->cities()->sync($validated['cities']);

        return redirect()->route('distributor.cities')
            ->with('success', 'Cidades atualizadas com sucesso!');
    }

    /**
     * Lista mensagens de contato recebidas
     */
    public function messages(Request $request)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $query = ContactMessage::where('distributor_id', $distributor->id)
            ->with(['seller', 'product']);

        // Filtro por status (lida/não lida)
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        // Filtro por vendedor
        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('distributor.messages.index', compact('distributor', 'messages'));
    }

    /**
     * Exibe uma mensagem específica
     */
    public function showMessage($id)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $message = ContactMessage::where('distributor_id', $distributor->id)
            ->with(['seller', 'product'])
            ->findOrFail($id);

        // Marca como lida se ainda não foi
        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return view('distributor.messages.show', compact('distributor', 'message'));
    }

    /**
     * Lista vendedores do distribuidor
     */
    public function sellers()
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $sellers = $distributor->sellers()->paginate(15);

        return view('distributor.sellers.index', compact('distributor', 'sellers'));
    }

    /**
     * Exibe formulário de criação de vendedor
     */
    public function createSeller()
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        return view('distributor.sellers.create', compact('distributor'));
    }

    /**
     * Salva novo vendedor
     */
    public function storeSeller(Request $request)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        $validated['distributor_id'] = $distributor->id;

        Seller::create($validated);

        return redirect()->route('distributor.sellers')
            ->with('success', 'Vendedor cadastrado com sucesso!');
    }

    /**
     * Exibe formulário de edição de vendedor
     */
    public function editSeller($id)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $seller = Seller::where('distributor_id', $distributor->id)->findOrFail($id);

        return view('distributor.sellers.edit', compact('distributor', 'seller'));
    }

    /**
     * Atualiza vendedor
     */
    public function updateSeller(Request $request, $id)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $seller = Seller::where('distributor_id', $distributor->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:100',
        ]);

        $seller->update($validated);

        return redirect()->route('distributor.sellers')
            ->with('success', 'Vendedor atualizado com sucesso!');
    }

    /**
     * Exclui vendedor
     */
    public function destroySeller($id)
    {
        $distributor = $this->getDistributor();

        if (!$distributor) {
            return redirect()->route('distributor.login');
        }

        $seller = Seller::where('distributor_id', $distributor->id)->findOrFail($id);
        $seller->delete();

        return redirect()->route('distributor.sellers')
            ->with('success', 'Vendedor excluído com sucesso!');
    }

    /**
     * API: Retorna estados para o seletor de cidades
     */
    public function getStates()
    {
        $states = State::orderBy('name')->get(['id', 'name', 'uf']);
        return response()->json($states);
    }

    /**
     * API: Retorna cidades de um estado
     */
    public function getCitiesByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($cities);
    }
}
