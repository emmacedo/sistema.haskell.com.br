<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with(['distributor', 'city']);

        // Filtro de lidas/não lidas
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        // Busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sender_name', 'like', "%{$search}%")
                    ->orWhere('sender_email', 'like', "%{$search}%")
                    ->orWhere('sender_phone', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        // Marcar como lida automaticamente
        $contactMessage->markAsRead();

        // Carrega todos os relacionamentos necessários (com verificações de existência)
        $contactMessage->load([
            'distributor.cities.state', // Distribuidor e suas cidades atendidas
            'city.state',               // Cidade antiga (relacionamento legado)
            'product',                  // Produto de interesse
            'seller',                   // Vendedor específico contatado
        ]);

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(ContactMessage $contactMessage)
    {
        try {
            $contactMessage->markAsRead();

            return redirect()
                ->back()
                ->with('success', 'Mensagem marcada como lida!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao marcar mensagem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        try {
            $contactMessage->delete();

            return redirect()
                ->route('contact-messages.index')
                ->with('success', 'Mensagem excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir mensagem: ' . $e->getMessage());
        }
    }
}
