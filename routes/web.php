<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ESTRUTURA DO SISTEMA:
|
| 1. PÁGINAS PÚBLICAS (Frontend para clientes)
|    - / ou /busca ou /buscar → Busca de distribuidores (página para incluir no site)
|    - /cadastro → Cadastro de novos distribuidores
|
| 2. ÁREA DO DISTRIBUIDOR
|    - /login → Login do distribuidor para editar seus dados
|    - /home → Dashboard do distribuidor após login
|
| 3. PAINEL ADMINISTRATIVO (AdminLTE)
|    - /admin → Login do administrador
|    - /admin/dashboard → Painel de controle
|    - /admin/* → Todas funcionalidades administrativas
|
*/

// =============================================================================
// 1. PÁGINAS PÚBLICAS - Frontend para Clientes
// =============================================================================

// Página inicial - Busca de distribuidores (para incluir no site)
Route::get('/', [App\Http\Controllers\Frontend\DistributorSearchController::class, 'index'])
    ->name('search.index');
Route::get('/busca', [App\Http\Controllers\Frontend\DistributorSearchController::class, 'index'])
    ->name('search.busca');
Route::get('/buscar', [App\Http\Controllers\Frontend\DistributorSearchController::class, 'index'])
    ->name('search.buscar');
Route::post('/busca', [App\Http\Controllers\Frontend\DistributorSearchController::class, 'search'])
    ->name('search.search');
Route::get('/busca/autocomplete', [App\Http\Controllers\Frontend\DistributorSearchController::class, 'autocomplete'])
    ->name('search.autocomplete');

// Formulário de contato com vendedor
Route::get('/contato/produtos', [App\Http\Controllers\Frontend\ContactController::class, 'getProducts'])
    ->name('contact.products');
Route::post('/contato', [App\Http\Controllers\Frontend\ContactController::class, 'store'])
    ->middleware('throttle:10,1') // Máximo 10 mensagens por minuto por IP
    ->name('contact.store');

// Cadastro de novos distribuidores (auto-cadastro)
Route::get('/cadastro', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'create'])
    ->name('registration.create');
// Proteção contra spam: máximo 3 tentativas de cadastro por hora por IP
Route::post('/cadastro', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'store'])
    ->middleware('throttle:3,60')
    ->name('registration.store');
Route::get('/cadastro/sucesso', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'success'])
    ->name('registration.success');
// Proteção contra tentativas de brute force: máximo 5 tentativas de verificação a cada 10 minutos
Route::post('/cadastro/verificar', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'verify'])
    ->middleware('throttle:5,10')
    ->name('registration.verify');
Route::get('/cadastro/verificado', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'verified'])
    ->name('registration.verified');
// Proteção contra flood de emails: máximo 2 reenvios a cada 10 minutos
Route::post('/cadastro/reenviar', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'resendCode'])
    ->middleware('throttle:2,10')
    ->name('registration.resend');
Route::get('/cadastro/cidades/autocomplete', [App\Http\Controllers\Frontend\DistributorRegistrationController::class, 'citiesAutocomplete'])
    ->name('registration.cities.autocomplete');

// =============================================================================
// 2. ÁREA DO DISTRIBUIDOR - Login passwordless por código de e-mail
// =============================================================================

// Login do distribuidor (solicita código)
Route::get('/login', [App\Http\Controllers\Auth\DistributorLoginController::class, 'showLoginForm'])
    ->name('distributor.login');
Route::post('/login', [App\Http\Controllers\Auth\DistributorLoginController::class, 'sendCode'])
    ->name('distributor.login.send');

// Verificação do código
Route::get('/login/verificar', [App\Http\Controllers\Auth\DistributorLoginController::class, 'showVerifyForm'])
    ->name('distributor.login.verify.form');
Route::post('/login/verificar', [App\Http\Controllers\Auth\DistributorLoginController::class, 'verifyCode'])
    ->name('distributor.login.verify');

// Reenviar código
Route::post('/login/reenviar', [App\Http\Controllers\Auth\DistributorLoginController::class, 'resendCode'])
    ->name('distributor.login.resend');

// Logout do distribuidor
Route::post('/logout', [App\Http\Controllers\Auth\DistributorLoginController::class, 'logout'])
    ->name('distributor.logout');

// Área do distribuidor (protegida)
Route::prefix('painel')->middleware(['distributor.auth'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Distributor\PanelController::class, 'index'])
        ->name('distributor.dashboard');

    // Dados da empresa (perfil do distribuidor)
    Route::get('/empresa', [App\Http\Controllers\Distributor\PanelController::class, 'editProfile'])
        ->name('distributor.profile.edit');
    Route::put('/empresa', [App\Http\Controllers\Distributor\PanelController::class, 'updateProfile'])
        ->name('distributor.profile.update');

    // Cidades atendidas
    Route::get('/cidades', [App\Http\Controllers\Distributor\PanelController::class, 'cities'])
        ->name('distributor.cities');
    Route::put('/cidades', [App\Http\Controllers\Distributor\PanelController::class, 'updateCities'])
        ->name('distributor.cities.update');

    // Mensagens de contato
    Route::get('/mensagens', [App\Http\Controllers\Distributor\PanelController::class, 'messages'])
        ->name('distributor.messages');
    Route::get('/mensagens/{id}', [App\Http\Controllers\Distributor\PanelController::class, 'showMessage'])
        ->name('distributor.messages.show');

    // Vendedores
    Route::get('/vendedores', [App\Http\Controllers\Distributor\PanelController::class, 'sellers'])
        ->name('distributor.sellers');
    Route::get('/vendedores/novo', [App\Http\Controllers\Distributor\PanelController::class, 'createSeller'])
        ->name('distributor.sellers.create');
    Route::post('/vendedores', [App\Http\Controllers\Distributor\PanelController::class, 'storeSeller'])
        ->name('distributor.sellers.store');
    Route::get('/vendedores/{id}/editar', [App\Http\Controllers\Distributor\PanelController::class, 'editSeller'])
        ->name('distributor.sellers.edit');
    Route::put('/vendedores/{id}', [App\Http\Controllers\Distributor\PanelController::class, 'updateSeller'])
        ->name('distributor.sellers.update');
    Route::delete('/vendedores/{id}', [App\Http\Controllers\Distributor\PanelController::class, 'destroySeller'])
        ->name('distributor.sellers.destroy');

    // API endpoints para seleção de cidades
    Route::get('/api/states', [App\Http\Controllers\Distributor\PanelController::class, 'getStates'])
        ->name('distributor.api.states');
    Route::get('/api/cities/{stateId}', [App\Http\Controllers\Distributor\PanelController::class, 'getCitiesByState'])
        ->name('distributor.api.cities');
});

// =============================================================================
// 3. PAINEL ADMINISTRATIVO - AdminLTE (Sistema separado)
// =============================================================================

// Login do administrador (GET)
Route::get('/admin', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'showLoginForm'])
    ->name('admin.login');

// Login do administrador (POST)
Route::post('/admin', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'login'])
    ->name('admin.login.submit');

// Logout do administrador
Route::post('/admin/logout', [App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'logout'])
    ->name('admin.logout');

// Rotas administrativas protegidas
Route::prefix('admin')->middleware(['auth', App\Http\Middleware\CheckAdmin::class])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Estados
    Route::get('/states', [App\Http\Controllers\Admin\StateController::class, 'index'])->name('admin.states.index');

    // Cidades (busca AJAX)
    Route::get('/cities/search', [App\Http\Controllers\Admin\CityController::class, 'search'])->name('admin.cities.search');
    Route::get('/cities/by-state/{state}', [App\Http\Controllers\Admin\CityController::class, 'byState'])->name('admin.cities.by-state');

    // Distribuidores
    Route::resource('distributors', App\Http\Controllers\Admin\DistributorController::class);
    // Rotas de aprovação/rejeição usando model binding {distributor}
    Route::post('distributors/{distributor}/approve', [App\Http\Controllers\Admin\DistributorController::class, 'approve'])->name('distributors.approve');
    Route::post('distributors/{distributor}/reject', [App\Http\Controllers\Admin\DistributorController::class, 'reject'])->name('distributors.reject');

    // Vendedores
    Route::resource('sellers', App\Http\Controllers\Admin\SellerController::class);

    // Mensagens de contato
    Route::resource('contact-messages', App\Http\Controllers\Admin\ContactMessageController::class);
    // Rota para marcar mensagem como lida manualmente (usa model binding)
    Route::post('contact-messages/{contactMessage}/mark-as-read', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('contact-messages.mark-as-read');

    // Produtos de interesse
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ])->except(['show']);
    Route::post('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('admin.products.toggle-status');
});
