<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Transaction;
use App\Services\EscrowService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private EscrowService $escrowService,
    ) {}

    // Show all purchases and sales
    public function index()
    {
        $purchases = Transaction::with(['listing', 'seller'])
            ->where('buyer_id', auth()->id())
            ->latest()
            ->paginate(10, ['*'], 'purchases_page');

        $sales = Transaction::with(['listing', 'buyer'])
            ->where('seller_id', auth()->id())
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        return view('transactions.index', compact('purchases', 'sales'));
    }

    // Show single transaction detail
    public function show(Transaction $transaction)
    {
        // Only buyer or seller can view
        if (
            $transaction->buyer_id  !== auth()->id() &&
            $transaction->seller_id !== auth()->id()
        ) {
            abort(403);
        }

        $transaction->load(['listing', 'buyer', 'seller', 'escrowLog']);
        return view('transactions.show', compact('transaction'));
    }

    // Buyer clicks "Buy Now"
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        // Cannot buy inactive listing
        if ($listing->status !== 'active') {
            return back()->with('error', 'This listing is no longer available.');
        }

        // Cannot buy your own listing
        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot buy your own listing.');
        }

        // Check wallet balance
        if (!$this->walletService->hasSufficientBalance(auth()->id(), $listing->price)) {
            return redirect()
                ->route('wallet.index')
                ->with('error', 'Insufficient balance. Please top up your wallet first.');
        }

        // Calculate fees
        $fee    = round($listing->price * 0.05, 2); // 5% platform fee
        $payout = round($listing->price - $fee, 2);

        // Create the transaction
        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'listing_id'       => $listing->id,
            'buyer_id'         => auth()->id(),
            'seller_id'        => $listing->user_id,
            'amount'           => $listing->price,
            'platform_fee'     => $fee,
            'seller_payout'    => $payout,
            'payment_method'   => 'wallet',
        ]);

        // Hold funds in escrow
        $this->escrowService->hold($transaction);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Purchase successful! Funds are held in escrow.');
    }

    // Buyer clicks "Confirm Receipt"
    public function confirm(Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'escrow') {
            return back()->with('error', 'This transaction cannot be confirmed.');
        }

        $this->escrowService->release($transaction, 'buyer');

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Payment released to seller. Thank you!');
    }

    // Buyer clicks "Raise Dispute"
    public function dispute(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'escrow') {
            return back()->with('error', 'Cannot raise dispute on this transaction.');
        }

        $transaction->update(['status' => 'disputed']);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Dispute raised. Admin will review within 24-48 hours.');
    }
}
