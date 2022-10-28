<?php

use Illuminate\Support\Facades\Route;

/**
 * 'guest' middleware applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::post('{company_id}/gerencianet/webhook/{webhook_secret}',
    'Modules\Gerencianet\Http\Controllers\Webhook@index')
    ->name('gerencianet.invoices.webhook');
