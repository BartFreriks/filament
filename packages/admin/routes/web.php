<?php

use Filament\Facades\Filament;
use Filament\Http\Controllers\AssetController;
use Filament\Http\Livewire\Login;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->name('filament.')
    ->prefix(config('filament.path'))
    ->group(function () {
        Route::get('/login', Login::class)->name('auth.login');

        Route::get('/assets/{path}', AssetController::class)->where('path', '.*')->name('asset');

        Route::middleware(config('filament.middleware.auth'))->group(function (): void {
            Route::name('pages.')->group(function (): void {
                foreach (Filament::getPages() as $page) {
                    Route::group([], $page::getRoutes());
                }
            });

            Route::name('resources.')->group(function (): void {
                foreach (Filament::getResources() as $resource) {
                    Route::group([], $resource::getRoutes());
                }
            });

            Route::get('/logout', function (): RedirectResponse {
                Filament::auth()->logout();

                return redirect()->route('filament.auth.login');
            })->name('auth.logout');
        });
    });