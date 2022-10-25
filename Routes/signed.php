<?php

use Illuminate\Support\Facades\Route;

/**
 * 'signed' middleware and 'signed/gerencianet' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::signed('gerencianet', function () {
    Route::get('invoices/{invoice}', 'Payment@show')->name('invoices.show');
});
