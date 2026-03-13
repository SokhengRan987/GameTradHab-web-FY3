<?php

namespace App\Http\Controllers;

use App\Models\WalletLog;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
    ) {}

    // Show wallet page
    public function index()
    {
        $logs = WalletLog::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('wallet.index', compact('logs'));
    }

    // Top up wallet
    // NOTE: This is direct credit for development
    // Replace with Stripe in production
    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
        ]);

        $this->walletService->credit(
            userId:      auth()->id(),
            amount:      $request->amount,
            type:        'topup',
            description: 'Wallet top-up'
        );

        return back()->with('success', '$' . number_format($request->amount, 2) . ' added to your wallet.');
    }
}
