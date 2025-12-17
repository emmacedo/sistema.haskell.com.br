<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Distributor;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Envia email de verificação para o distribuidor
     *
     * @param Distributor $distributor
     * @param string $verificationCode
     * @return bool
     */
    public function sendVerificationEmail(Distributor $distributor, string $verificationCode): bool
    {
        try {
            Mail::send('emails.verification', [
                'distributor' => $distributor,
                'code' => $verificationCode,
            ], function ($message) use ($distributor) {
                $message->to($distributor->email, $distributor->trade_name)
                    ->subject('Verificação de Email - Sistema de Distribuidores');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de verificação: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envia email de boas-vindas após aprovação
     *
     * @param Distributor $distributor
     * @return bool
     */
    public function sendWelcomeEmail(Distributor $distributor): bool
    {
        try {
            Mail::send('emails.welcome', [
                'distributor' => $distributor,
            ], function ($message) use ($distributor) {
                $message->to($distributor->email, $distributor->trade_name)
                    ->subject('Cadastro Aprovado - Sistema de Distribuidores');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de boas-vindas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envia email de rejeição
     *
     * @param Distributor $distributor
     * @return bool
     */
    public function sendRejectionEmail(Distributor $distributor): bool
    {
        try {
            Mail::send('emails.rejection', [
                'distributor' => $distributor,
                'reason' => $distributor->rejection_reason,
            ], function ($message) use ($distributor) {
                $message->to($distributor->email, $distributor->trade_name)
                    ->subject('Cadastro Não Aprovado - Sistema de Distribuidores');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de rejeição: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envia notificação de nova mensagem de contato para o vendedor
     *
     * @param ContactMessage $contactMessage
     * @return bool
     */
    public function sendContactToSeller(ContactMessage $contactMessage): bool
    {
        try {
            $seller = $contactMessage->seller;

            // Se não tem vendedor ou o vendedor não tem email, não envia
            if (!$seller || !$seller->email) {
                Log::warning('Vendedor sem email para mensagem de contato ID: ' . $contactMessage->id);
                return false;
            }

            Mail::send('emails.contact-to-seller', [
                'contactMessage' => $contactMessage,
                'seller' => $seller,
                'distributor' => $contactMessage->distributor,
            ], function ($message) use ($seller, $contactMessage) {
                $message->to($seller->email, $seller->name)
                    ->subject('Nova Mensagem de Contato - ' . $contactMessage->sender_name)
                    ->replyTo($contactMessage->sender_email, $contactMessage->sender_name);
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de contato para vendedor: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envia notificação de nova mensagem de contato para os administradores
     *
     * @param ContactMessage $contactMessage
     * @return bool
     */
    public function sendContactToAdmin(ContactMessage $contactMessage): bool
    {
        try {
            // Buscar todos os administradores
            $admins = User::where('is_admin', true)->get();

            if ($admins->isEmpty()) {
                Log::warning('Nenhum administrador encontrado para notificação de contato.');
                return false;
            }

            foreach ($admins as $admin) {
                Mail::send('emails.contact-to-admin', [
                    'contactMessage' => $contactMessage,
                    'admin' => $admin,
                    'seller' => $contactMessage->seller,
                    'distributor' => $contactMessage->distributor,
                ], function ($message) use ($admin, $contactMessage) {
                    $message->to($admin->email, $admin->name)
                        ->subject('[Admin] Nova Mensagem de Contato - ' . $contactMessage->sender_name);
                });
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de contato para admin: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envia notificação de nova mensagem de contato (método legado)
     *
     * @param array $data
     * @return bool
     */
    public function sendContactNotification(array $data): bool
    {
        try {
            // Enviar para o email do distribuidor
            Mail::send('emails.contact-notification', $data, function ($message) use ($data) {
                $message->to($data['distributor_email'], $data['distributor_name'])
                    ->subject('Nova Mensagem de Contato');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de contato: ' . $e->getMessage());
            return false;
        }
    }
}
