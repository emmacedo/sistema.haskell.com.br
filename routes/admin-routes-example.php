<?php

/**
 * Rotas do Painel Administrativo
 *
 * Adicione estas rotas ao arquivo routes/web.php dentro de um grupo com middleware de autenticação
 *
 * Exemplo:
 *
 * Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
 *     // Cole as rotas abaixo aqui
 * });
 */

use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\ContactMessageController;

// Rotas de Distribuidores
Route::resource('distributors', DistributorController::class);
Route::put('distributors/{distributor}/approve', [DistributorController::class, 'approve'])
    ->name('distributors.approve');
Route::put('distributors/{distributor}/reject', [DistributorController::class, 'reject'])
    ->name('distributors.reject');

// Rotas de Vendedores
Route::resource('sellers', SellerController::class);

// Rotas de Mensagens de Contato
Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
Route::post('contact-messages/{contactMessage}/mark-as-read', [ContactMessageController::class, 'markAsRead'])
    ->name('contact-messages.mark-as-read');
