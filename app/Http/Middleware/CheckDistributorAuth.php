<?php

namespace App\Http\Middleware;

use App\Models\ContactMessage;
use App\Models\Distributor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckDistributorAuth
{
    /**
     * Handle an incoming request.
     *
     * Verifica se o distribuidor está autenticado e compartilha
     * dados globais (distribuidor e contagem de mensagens não lidas)
     * com todas as views do painel do distribuidor.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o distribuidor está logado via session
        if (!session('distributor_logged_in') || !session('distributor_id')) {
            return redirect()->route('distributor.login')
                           ->with('error', 'Você precisa fazer login para acessar esta página.');
        }

        // Busca o distribuidor logado
        $distributor = Distributor::find(session('distributor_id'));

        // Se o distribuidor não existe mais, faz logout
        if (!$distributor) {
            session()->forget(['distributor_id', 'distributor_logged_in']);
            return redirect()->route('distributor.login')
                           ->with('error', 'Sessão expirada. Faça login novamente.');
        }

        // Conta mensagens não lidas para exibir no menu
        $unreadMessages = ContactMessage::where('distributor_id', $distributor->id)
            ->whereNull('read_at')
            ->count();

        // Compartilha dados com todas as views
        View::share('distributor', $distributor);
        View::share('unreadMessages', $unreadMessages);

        return $next($request);
    }
}
