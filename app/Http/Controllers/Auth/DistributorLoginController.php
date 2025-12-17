<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DistributorLoginController extends Controller
{
    /**
     * Tempo de validade do código de verificação em minutos.
     * Após este período, o código expira e um novo deve ser solicitado.
     */
    private const CODE_EXPIRATION_MINUTES = 30;
    /**
     * Exibe o formulário de login (solicitar código)
     */
    public function showLoginForm()
    {
        return view('auth.distributor.login');
    }

    /**
     * Envia o código de acesso por e-mail
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:distributors,email',
        ], [
            'email.exists' => 'Não encontramos nenhum distribuidor cadastrado com este e-mail.',
        ]);

        $distributor = Distributor::where('email', $request->email)->first();

        // Verifica se o distribuidor está aprovado
        if ($distributor->status !== 'approved') {
            return back()->withErrors([
                'email' => 'Seu cadastro ainda não foi aprovado. Aguarde a aprovação do administrador.'
            ]);
        }

        // Gera um código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Salva o código no banco com data de expiração (30 minutos)
        $distributor->update([
            'verification_code' => $code,
            'verification_code_expires_at' => Carbon::now()->addMinutes(self::CODE_EXPIRATION_MINUTES),
        ]);

        // Envia o código por e-mail
        Mail::send('emails.distributor-login-code', [
            'code' => $code,
            'distributor' => $distributor
        ], function ($message) use ($distributor) {
            $message->to($distributor->email, $distributor->trade_name)
                    ->subject('Código de Acesso - Haskell Cosméticos');
        });

        // Armazena o email na sessão (persistente, não flash)
        // para que esteja disponível na verificação do código
        session(['distributor_login_email' => $request->email]);

        return redirect()->route('distributor.login.verify.form')
                        ->with('success', 'Código enviado para o seu e-mail!');
    }

    /**
     * Exibe o formulário para informar o código
     */
    public function showVerifyForm()
    {
        // Verifica se existe email na sessão de login
        if (!session('distributor_login_email')) {
            return redirect()->route('distributor.login');
        }

        // Passa o email para a view
        return view('auth.distributor.verify', [
            'email' => session('distributor_login_email')
        ]);
    }

    /**
     * Verifica o código e faz login
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // Recupera o email da sessão persistente
        $email = session('distributor_login_email');

        if (!$email) {
            return redirect()->route('distributor.login')
                           ->with('error', 'Sessão expirada. Solicite um novo código.');
        }

        $distributor = Distributor::where('email', $email)
                                  ->where('verification_code', $request->code)
                                  ->first();

        // Verifica se o código existe
        if (!$distributor) {
            return back()->withErrors([
                'code' => 'Código inválido.'
            ]);
        }

        // Verifica se o código expirou (30 minutos de validade)
        if ($distributor->verification_code_expires_at &&
            Carbon::parse($distributor->verification_code_expires_at)->isPast()) {
            // Limpa o código expirado para evitar tentativas futuras
            $distributor->update([
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);

            return back()->withErrors([
                'code' => 'Código expirado. Solicite um novo código.'
            ]);
        }

        // Limpa o código após uso bem-sucedido (impede reutilização)
        $distributor->update([
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        // Remove o email temporário da sessão de login
        session()->forget('distributor_login_email');

        // Faz login do distributor usando session
        session([
            'distributor_id' => $distributor->id,
            'distributor_logged_in' => true,
        ]);

        return redirect()->route('distributor.dashboard')
                        ->with('success', 'Login realizado com sucesso!');
    }

    /**
     * Logout do distribuidor
     */
    public function logout(Request $request)
    {
        session()->forget(['distributor_id', 'distributor_logged_in']);

        return redirect()->route('distributor.login')
                        ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Reenvia o código
     */
    public function resendCode(Request $request)
    {
        // Recupera o email da sessão persistente
        $email = session('distributor_login_email');

        if (!$email) {
            return redirect()->route('distributor.login')
                           ->with('error', 'Sessão expirada. Informe seu e-mail novamente.');
        }

        $distributor = Distributor::where('email', $email)->first();

        if (!$distributor) {
            session()->forget('distributor_login_email');
            return redirect()->route('distributor.login')
                           ->with('error', 'Distribuidor não encontrado.');
        }

        // Gera um novo código com nova data de expiração (30 minutos)
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $distributor->update([
            'verification_code' => $code,
            'verification_code_expires_at' => Carbon::now()->addMinutes(self::CODE_EXPIRATION_MINUTES),
        ]);

        // Envia o código por e-mail
        Mail::send('emails.distributor-login-code', [
            'code' => $code,
            'distributor' => $distributor
        ], function ($message) use ($distributor) {
            $message->to($distributor->email, $distributor->trade_name)
                    ->subject('Código de Acesso - Haskell Cosméticos');
        });

        return back()->with('success', 'Código reenviado para o seu e-mail!');
    }
}
