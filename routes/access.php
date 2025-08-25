<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('access.moto-link.test')->group(function () {
    require __DIR__.'/auth.php';

    Route::get('/', function () {
        return redirect(route('Access | Gate'));
    });
// , 'log_page_view'
    Route::group(['middleware' => ['auth']], function () {
        Volt::route('gate', 'Access.gate.index')->name('Access | Gate');

        Volt::route('account', 'Access.account.index')->name('Access | Account');

        Volt::route('system', 'Access.system.index')->name('Access | System');

        Volt::route('authorization', 'Access.authorization.index')->name('Access | Authorization');
    });
});
