<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Retorna os produtos ativos para o formulário de contato
     */
    public function getProducts()
    {
        $products = Product::active()->ordered()->get(['id', 'name']);

        return response()->json($products);
    }

    /**
     * Salva a mensagem de contato e envia emails
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'sender_name' => 'required|string|max:100',
            'sender_email' => 'required|email|max:100',
            'sender_phone' => 'required|string|max:20',
            'sender_city' => 'required|string|max:100',
            'sender_state' => 'required|string|max:2',
            'product_id' => 'nullable|exists:products,id',
            'message' => 'required|string|max:2000',
        ], [
            'seller_id.required' => 'Vendedor não identificado.',
            'sender_name.required' => 'O nome é obrigatório.',
            'sender_email.required' => 'O e-mail é obrigatório.',
            'sender_email.email' => 'Informe um e-mail válido.',
            'sender_phone.required' => 'O telefone é obrigatório.',
            'sender_city.required' => 'A cidade é obrigatória.',
            'sender_state.required' => 'O estado é obrigatório.',
            'message.required' => 'A mensagem é obrigatória.',
        ]);

        try {
            // Buscar o vendedor e seu distribuidor
            $seller = Seller::with('distributor')->findOrFail($validated['seller_id']);
            $distributor = $seller->distributor;

            // Criar a mensagem de contato
            $contactMessage = ContactMessage::create([
                'distributor_id' => $distributor->id,
                'seller_id' => $seller->id,
                'sender_name' => $validated['sender_name'],
                'sender_email' => $validated['sender_email'],
                'sender_phone' => $validated['sender_phone'],
                'sender_city' => $validated['sender_city'],
                'sender_state' => strtoupper($validated['sender_state']),
                'product_id' => $validated['product_id'] ?? null,
                'message' => $validated['message'],
            ]);

            // Carregar relacionamentos para o email
            $contactMessage->load(['product', 'seller', 'distributor']);

            // Enviar email para o vendedor
            $this->emailService->sendContactToSeller($contactMessage);

            // Enviar email para o(s) administrador(es)
            $this->emailService->sendContactToAdmin($contactMessage);

            return response()->json([
                'success' => true,
                'message' => 'Mensagem enviada com sucesso! O vendedor entrará em contato em breve.',
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar mensagem de contato: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem. Por favor, tente novamente.',
            ], 500);
        }
    }
}
