<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route resource biar lebih rapi
    Route::resource('penawaran', PenawaranController::class);
    Route::resource('deal', DealController::class)->only(['index', 'show', 'edit', 'update']);
    Route::post('/deal/{id}/tasks', [DealController::class, 'storeTask'])->name('deal.tasks.store');
    Route::patch('/deal/{id}/tasks/{taskId}', [DealController::class, 'updateTask'])->name('deal.tasks.update');
    Route::patch('/deal/{id}/tasks/{taskId}/done', [DealController::class, 'markTaskDone'])->name('deal.tasks.done');
    Route::patch('/penawaran/{id}/status', [PenawaranController::class, 'updateStatus'])
        ->name('penawaran.status.update');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
    Route::get('/search', [SearchController::class, 'search'])->name('search');
});

require __DIR__ . '/auth.php';
