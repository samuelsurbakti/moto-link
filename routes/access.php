<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('access.moto-link.test')->group(function () {
    require __DIR__.'/auth.php';

    Route::get('/', function () {
        return redirect(route('Access | Gate'));
    });
// , 'log_page_view'
    Route::group(['middleware' => ['auth', 'log_page_view']], function () {
        Volt::route('gate', 'access.gate.index')->name('Access | Gate');

        Route::group(['middleware' => ['permission:Access - Akun'], 'prefix' => 'account'], function () {
            Volt::route('', 'access.account.index')->name('Access | Account');

            Route::group(['middleware' => ['permission:Access - Akun - Melihat Data'], 'prefix' => '{id}'], function () {
                Volt::route('', 'access.account.show')->name('Access | Account | Show');
            });
        });

        Volt::route('system', 'access.system.index')->name('Access | System');

        Volt::route('authorization', 'access.authorization.index')->name('Access | Authorization');
    });
});
