<?php

use Illuminate\Support\Facades\Route;

Route::admin('gerencianet', function () {
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', 'Settings@edit')
            ->defaults('alias', 'gerencianet')
            ->name('edit');
        Route::patch('/', 'Settings@update')
            ->defaults('alias', 'gerencianet')
            ->name('update');
    });

    Route::resource('transactions', 'Transactions');
    Route::resource('logs', 'Logs');
});
