<?php

use Illuminate\Support\Facades\Route;

/**
 * 'portal' middleware and 'portal/gerencianet' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::portal('gerencianet', function () {
    Route::get('invoices/{invoice}', 'Payment@show')->name('invoices.show');
});
