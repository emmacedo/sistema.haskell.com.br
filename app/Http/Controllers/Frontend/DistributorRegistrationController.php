<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Distributor;
use App\Models\Seller;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DistributorRegistrationController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Exibe o formulário de cadastro
     */
    public function create()
    {
        return view('frontend.registration.create');
    }

    /**
     * Processa o cadastro do distribuidor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'trade_name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:18|unique:distributors,cnpj,NULL,id,deleted_at,NULL',
            'email' => 'required|email|max:255|unique:distributors,email,NULL,id,deleted_at,NULL',
            'phone' => 'required|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'cities' => 'required|array|min:1',
            'cities.*' => 'exists:cities,id',

            // Pelo menos 1 vendedor é obrigatório
            'sellers' => 'required|array|min:1',
            'sellers.*.name' => 'required|string|max:255',
            'sellers.*.email' => 'required|email|max:255',
            'sellers.*.phone' => 'required|string|max:20',
            'sellers.*.whatsapp' => 'nullable|string|max:20',
        ], [
            'company_name.required' => 'A razão social é obrigatória',
            'trade_name.required' => 'O nome fantasia é obrigatório',
            'cnpj.required' => 'O CNPJ é obrigatório',
            'cnpj.size' => 'O CNPJ deve estar no formato 00.000.000/0000-00',
            'cnpj.unique' => 'Este CNPJ já está cadastrado',
            'email.required' => 'O email é obrigatório',
            'email.unique' => 'Este email já está cadastrado',
            'phone.required' => 'O telefone é obrigatório',
            'cep.required' => 'O CEP é obrigatório',
            'logradouro.required' => 'O logradouro é obrigatório',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'cidade.required' => 'A cidade é obrigatória',
            'estado.required' => 'O estado (UF) é obrigatório',
            'estado.size' => 'O estado deve ter 2 caracteres (UF)',
            'cities.required' => 'Selecione pelo menos uma cidade',
            'cities.min' => 'Selecione pelo menos uma cidade',
            'sellers.required' => 'Cadastre pelo menos um vendedor',
            'sellers.min' => 'Cadastre pelo menos um vendedor',
            'sellers.*.name.required' => 'O nome do vendedor é obrigatório',
            'sellers.*.email.required' => 'O email do vendedor é obrigatório',
            'sellers.*.phone.required' => 'O telefone do vendedor é obrigatório',
        ]);

        DB::beginTransaction();

        try {
            // Gerar código de verificação (6 caracteres alfanuméricos)
            $verificationCode = strtoupper(Str::random(6));

            // Definir expiração do código para 24 horas
            $expiresAt = now()->addHours(24);

            // Criar distribuidor
            $distributor = Distributor::create([
                'company_name' => $validated['company_name'],
                'trade_name' => $validated['trade_name'],
                'cnpj' => $validated['cnpj'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'phone2' => $validated['phone2'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'website' => $validated['website'] ?? null,
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'estado' => $validated['estado'],
                'status' => 'pending',
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => $expiresAt,
                'email_verified_at' => null,
            ]);

            // Associar cidades
            $distributor->cities()->attach($validated['cities']);

            // Criar vendedores
            foreach ($validated['sellers'] as $sellerData) {
                Seller::create([
                    'distributor_id' => $distributor->id,
                    'name' => $sellerData['name'],
                    'email' => $sellerData['email'],
                    'phone' => $sellerData['phone'],
                    'whatsapp' => $sellerData['whatsapp'] ?? null,
                ]);
            }

            // Enviar email de verificação
            $emailSent = $this->emailService->sendVerificationEmail($distributor, $verificationCode);

            if (!$emailSent) {
                // Log de falha no envio de email
                \Log::warning("Email de verificação não foi enviado", [
                    'distributor_email' => $distributor->email,
                    'distributor_id' => $distributor->id,
                ]);
                // Não falhar o cadastro, apenas logar o problema
                // O usuário pode reenviar o código depois na tela de sucesso
            }

            DB::commit();

            return redirect()
                ->route('registration.success')
                ->with('email', $distributor->email)
                ->with('email_send_failed', !$emailSent); // Flag para exibir alerta na view

        } catch (\Exception $e) {
            DB::rollBack();

            // Log sanitizado sem expor dados sensíveis (LGPD compliance)
            \Log::error('Erro ao cadastrar distribuidor', [
                'exception_message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                // NÃO logar: trace completo, dados do request, CNPJ, email, etc.
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Ocorreu um erro ao processar seu cadastro. Por favor, tente novamente.']);
        }
    }

    /**
     * Exibe página de sucesso
     */
    public function success()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('registration.create');
        }

        return view('frontend.registration.success', compact('email'));
    }

    /**
     * Processa a verificação do email
     *
     * Valida o código de verificação enviado por email, verificando:
     * - Email e código correspondem a um distribuidor
     * - Código ainda não foi usado (email_verified_at é null)
     * - Código não está expirado (válido por 24h)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ], [
            'email.required' => 'O email é obrigatório',
            'code.required' => 'O código de verificação é obrigatório',
            'code.size' => 'O código deve ter 6 caracteres',
        ]);

        // Buscar distribuidor com código válido e não expirado
        $distributor = Distributor::where('email', $request->email)
            ->where('verification_code', strtoupper($request->code))
            ->whereNull('email_verified_at')
            ->first();

        if (!$distributor) {
            return back()->withErrors([
                'code' => 'Código de verificação inválido ou email já verificado.'
            ]);
        }

        // Verificar se o código expirou (24 horas após geração)
        if ($distributor->verification_code_expires_at && $distributor->verification_code_expires_at->isPast()) {
            return back()->withErrors([
                'code' => 'Este código de verificação expirou. Por favor, solicite um novo código.'
            ]);
        }

        // Marcar email como verificado e limpar código
        $distributor->update([
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        return redirect()
            ->route('registration.verified')
            ->with('distributor', $distributor->trade_name);
    }

    /**
     * Exibe página de email verificado
     */
    public function verified()
    {
        $distributorName = session('distributor');

        if (!$distributorName) {
            return redirect()->route('registration.create');
        }

        return view('frontend.registration.verified', compact('distributorName'));
    }

    /**
     * Reenviar código de verificação
     *
     * Gera um novo código de verificação com prazo de 24 horas e reenvia por email.
     * Protegido por rate limiting (2 tentativas a cada 10 minutos).
     */
    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $distributor = Distributor::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if (!$distributor) {
            return back()->withErrors([
                'email' => 'Email não encontrado ou já verificado.'
            ]);
        }

        // Gerar novo código com nova data de expiração
        $verificationCode = strtoupper(Str::random(6));

        $distributor->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addHours(24),
        ]);

        // Reenviar email
        $emailSent = $this->emailService->sendVerificationEmail($distributor, $verificationCode);

        if (!$emailSent) {
            // Log sanitizado de falha no reenvio
            \Log::warning("Falha ao reenviar email de verificação", [
                'distributor_email' => $distributor->email,
                'distributor_id' => $distributor->id,
            ]);
            return back()->withErrors([
                'email' => 'Não foi possível enviar o email. Tente novamente em alguns instantes.'
            ]);
        }

        return back()->with('success', 'Código de verificação reenviado com sucesso!');
    }

    /**
     * Autocomplete para cidades (usado no formulário)
     */
    public function citiesAutocomplete(Request $request)
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
}
