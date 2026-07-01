<?php

use App\Livewire\ActivateAccount; // ADDED: Import the Livewire activation component
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public onboarding activation route for students [1.2.3]
Route::get('/activate-account/{token}', ActivateAccount::class)->name('activate.account');
