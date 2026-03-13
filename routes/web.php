<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ROUTES ─────────────────────────────────────────
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');

// ── BREEZE AUTH ROUTES ────────────────────────────────────
require __DIR__.'/auth.php';

// ── AUTHENTICATED USER ROUTES ─────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $listings = \App\Models\Listing::with('game')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $purchases = \App\Models\Transaction::with('listing')
            ->where('buyer_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('listings', 'purchases'));
    })->name('dashboard');

    // Profile (Breeze built-in)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ↓ IMPORTANT: create and edit MUST come before {listing}
    Route::get('/listings/create', [ListingController::class, 'create'])
        ->name('listings.create');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])
        ->name('listings.edit');
    Route::post('/listings', [ListingController::class, 'store'])
        ->name('listings.store');
    Route::patch('/listings/{listing}', [ListingController::class, 'update'])
        ->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])
        ->name('listings.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');
    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirm'])
        ->name('transactions.confirm');
    Route::post('/transactions/{transaction}/dispute', [TransactionController::class, 'dispute'])
        ->name('transactions.dispute');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])
        ->name('wallet.index');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])
        ->name('wallet.topup');
});

// ↓ IMPORTANT: public show route comes AFTER the auth group
Route::get('/listings/{listing}', [ListingController::class, 'show'])
    ->name('listings.show');

// ── ADMIN ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/listings', [AdminListingController::class, 'index'])
        ->name('listings.index');
    Route::get('/listings/{listing}', [AdminListingController::class, 'show'])
        ->name('listings.show');
    Route::patch('/listings/{listing}/approve', [AdminListingController::class, 'approve'])
        ->name('listings.approve');
    Route::patch('/listings/{listing}/reject', [AdminListingController::class, 'reject'])
        ->name('listings.reject');

    Route::get('/transactions', [AdminTransactionController::class, 'index'])
        ->name('transactions.index');
    Route::patch('/transactions/{transaction}/release', [AdminTransactionController::class, 'releaseEscrow'])
        ->name('transactions.release');
    Route::patch('/transactions/{transaction}/refund', [AdminTransactionController::class, 'refund'])
        ->name('transactions.refund');
});
