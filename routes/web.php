<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TwoFactorController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware(['auth', 'verified', 'two-factor'])->group(function () {
    // Dashboard Routes
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Users Routes
    Route::resource('users', UserController::class)->except(['show']);

    // Roles Routes
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::post('roles/{role}/toggle-permission', [RoleController::class, 'togglePermission'])->name('roles.toggle-permission');
    
    Route::get('roles/search', [RoleController::class, 'search'])->name('roles.search');
    // Permissions Routes
    Route::resource('permissions', PermissionController::class)->except(['show']);

    // Two Factor Authentication Routes
    Route::get('two-factor-challenge', [TwoFactorController::class, 'create'])
        ->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorController::class, 'store'])
        ->name('two-factor.store');
    
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile')->middleware('two-factor');
    Volt::route('settings/password', 'settings.password')->name('settings.password')->middleware('two-factor');
    Volt::route('settings/two-factor', 'settings.two-factor')->name('settings.two-factor')->middleware('two-factor');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance')->middleware('two-factor');
});

require __DIR__.'/auth.php';
