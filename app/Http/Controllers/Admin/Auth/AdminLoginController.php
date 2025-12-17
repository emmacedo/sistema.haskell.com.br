<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    /**
     * Exibe o formulário de login do administrador
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Processa o login do administrador
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verifica se o usuário é administrador
            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // Se não for admin, faz logout e retorna erro
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['Você não tem permissão para acessar o painel administrativo.'],
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['As credenciais fornecidas estão incorretas.'],
        ]);
    }

    /**
     * Faz logout do administrador
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
