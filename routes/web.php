<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Database connection is successful!";
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});

Route::middleware(['auth'])->group(function () {
    // Redirect /dashboard based on role
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        if (in_array($role, ['administrasi', 'eksekutif', 'keuangan'])) {
            return redirect()->route($role . '.dashboard');
        }
        abort(403, 'Unauthorized role.');
    })->name('dashboard');

    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/api/deadline-notifications', [App\Http\Controllers\Administrasi\DealController::class, 'getDeadlineNotifications'])->name('api.deadline.notifications');

    // Route khusus administrasi
    Route::middleware(['role:administrasi'])->prefix('administrasi')->name('administrasi.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Administrasi\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('penawaran', App\Http\Controllers\Administrasi\PenawaranController::class);
        Route::resource('deal', App\Http\Controllers\Administrasi\DealController::class)->only(['index', 'show', 'edit', 'update']);
        Route::post('/deal/{id}/tasks', [App\Http\Controllers\Administrasi\DealController::class, 'storeTask'])->name('deal.tasks.store');
        Route::patch('/deal/{id}/tasks/{taskId}', [App\Http\Controllers\Administrasi\DealController::class, 'updateTask'])->name('deal.tasks.update');
        Route::patch('/deal/{id}/tasks/{taskId}/done', [App\Http\Controllers\Administrasi\DealController::class, 'markTaskDone'])->name('deal.tasks.done');
        Route::get('/penawaran/{id}/invoice', [App\Http\Controllers\Administrasi\PenawaranController::class, 'invoice'])->name('penawaran.invoice');
        Route::get('/deal/{id}/invoice', [App\Http\Controllers\Administrasi\DealController::class, 'invoice'])->name('deal.invoice');
        Route::patch('/penawaran/{id}/status', [App\Http\Controllers\Administrasi\PenawaranController::class, 'updateStatus'])->name('penawaran.status.update');
    });

    // Route khusus eksekutif
    Route::middleware(['role:eksekutif'])->prefix('eksekutif')->name('eksekutif.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Eksekutif\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('penawaran', App\Http\Controllers\Eksekutif\PenawaranController::class)->only(['index', 'show']);
        Route::resource('deal', App\Http\Controllers\Eksekutif\DealController::class)->only(['index', 'show']);
        Route::get('/penawaran/{id}/invoice', [App\Http\Controllers\Eksekutif\PenawaranController::class, 'invoice'])->name('penawaran.invoice');
        Route::get('/deal/{id}/invoice', [App\Http\Controllers\Eksekutif\DealController::class, 'invoice'])->name('deal.invoice');
    });

    // Route khusus keuangan
    Route::middleware(['role:keuangan'])->prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Keuangan\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('deal', App\Http\Controllers\Keuangan\DealController::class)->only(['index', 'show']);
        Route::get('/deal/{id}/invoice', [App\Http\Controllers\Keuangan\DealController::class, 'invoice'])->name('deal.invoice');
        Route::patch('/deal/{id}/tasks/{taskId}/invoice', [App\Http\Controllers\Keuangan\DealController::class, 'updateInvoiceTask'])->name('deal.tasks.invoice.update');
        Route::get('/tagihan', [App\Http\Controllers\Keuangan\TagihanController::class, 'index'])->name('tagihan.index');
        Route::patch('/tagihan/{id}/status', [App\Http\Controllers\Keuangan\TagihanController::class, 'updateStatus'])->name('tagihan.status.update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

require __DIR__ . '/auth.php';

