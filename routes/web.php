<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ROUTES ─────────────────────────────────────────
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

// Seller Profile
Route::get('/sellers/{user}', [App\Http\Controllers\SellerProfileController::class, 'show'])
    ->name('sellers.show');

// Listings create (public for testing)
Route::get('/listings/create', [ListingController::class, 'create'])
    ->name('listings.create');

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

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::patch('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Seller onboarding
    Route::get('/sell/onboarding', [App\Http\Controllers\SellerOnboardingController::class, 'show'])
        ->name('seller.onboarding');
    Route::post('/sell/onboarding', [App\Http\Controllers\SellerOnboardingController::class, 'store'])
        ->name('seller.onboarding.store');

    // Listings — create/edit MUST come before {listing} wildcard
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])
        ->name('listings.edit');
    Route::post('/listings', [ListingController::class, 'store'])
        ->name('listings.store');
    Route::patch('/listings/{listing}', [ListingController::class, 'update'])
        ->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])
        ->name('listings.destroy');

    // Reports
    Route::get('/listings/{listing}/report', [ReportController::class, 'create'])
        ->name('listings.report');
    Route::post('/listings/{listing}/report', [ReportController::class, 'store'])
        ->name('listings.report.store');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store')
        ->middleware('throttle:5,1');
    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirm'])
        ->name('transactions.confirm');
    Route::post('/transactions/{transaction}/dispute', [TransactionController::class, 'dispute'])
        ->name('transactions.dispute');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show');

    // Reviews
    Route::post('/reviews/{transaction}', [ReviewController::class, 'store'])
        ->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])
        ->name('wallet.index');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])
        ->name('wallet.topup');

    // Auctions — create/my-bids MUST come before {listing} wildcard
    Route::get('/auctions/my-bids', [AuctionController::class, 'myBids'])
        ->name('auctions.my-bids');
    Route::get('/auctions/create', [AuctionController::class, 'create'])
        ->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])
        ->name('auctions.store');
    Route::post('/auctions/{listing}/bid', [AuctionController::class, 'bid'])
        ->name('auctions.bid');
});

// ── ADMIN ROUTES ──────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Listings
    Route::get('/listings', [AdminListingController::class, 'index'])
        ->name('listings.index');
    Route::get('/listings/{listing}', [AdminListingController::class, 'show'])
        ->name('listings.show');
    Route::patch('/listings/{listing}/remove', [AdminListingController::class, 'remove'])
        ->name('listings.remove');
    Route::patch('/listings/{listing}/unflag', [AdminListingController::class, 'unflag'])
        ->name('listings.unflag');

    // Auctions
    Route::get('/auctions', [AdminAuctionController::class, 'index'])
        ->name('auctions.index');
    Route::get('/auctions/{listing}', [AdminAuctionController::class, 'show'])
        ->name('auctions.show');
    Route::patch('/auctions/{listing}/remove', [AdminAuctionController::class, 'remove'])
        ->name('auctions.remove');
    Route::patch('/auctions/{listing}/end', [AdminAuctionController::class, 'end'])
        ->name('auctions.end');

    // Transactions
    Route::get('/transactions', [AdminTransactionController::class, 'index'])
        ->name('transactions.index');
    Route::patch('/transactions/{transaction}/release', [AdminTransactionController::class, 'releaseEscrow'])
        ->name('transactions.release');
    Route::patch('/transactions/{transaction}/refund', [AdminTransactionController::class, 'refund'])
        ->name('transactions.refund');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])
        ->name('reports.index');
    Route::patch('/reports/{report}/remove-listing', [AdminReportController::class, 'removeListing'])
        ->name('reports.remove');
    Route::patch('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])
        ->name('reports.dismiss');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('users.index');
    Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])
        ->name('users.ban');
    Route::patch('/users/{user}/unban', [AdminUserController::class, 'unban'])
        ->name('users.unban');
    Route::patch('/users/{user}/verify', [AdminUserController::class, 'verify'])
        ->name('users.verify');
});

// ── PUBLIC DETAIL ROUTES ──────────────────────────────────
// These MUST be last so /create and /my-bids are matched first
Route::get('/listings/{listing}', [ListingController::class, 'show'])
    ->name('listings.show');
Route::get('/auctions/{listing}', [AuctionController::class, 'show'])
    ->name('auctions.show');
