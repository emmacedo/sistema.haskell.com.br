<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    // Dashboard
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

    // Distribuidores
    Route::crud('distributor', 'DistributorCrudController');
    Route::post('distributor/{id}/approve', 'DistributorCrudController@approve')->name('distributor.approve');
    Route::get('distributor/{id}/reject', 'DistributorCrudController@reject')->name('distributor.reject');
    Route::post('distributor/{id}/process-rejection', 'DistributorCrudController@processRejection')->name('distributor.process-rejection');

    // Outros CRUDs
    Route::crud('seller', 'SellerCrudController');
    Route::crud('contact-message', 'ContactMessageCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
