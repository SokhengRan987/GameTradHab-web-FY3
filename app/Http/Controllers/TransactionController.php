<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
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

    // Show single transaction
    public function show(Transaction $transaction)
    {
        if (
            $transaction->buyer_id  !== auth()->id() &&
            $transaction->seller_id !== auth()->id()
        ) {
            abort(403);
        }

        $transaction->load(['listing', 'buyer', 'seller', 'escrowLog', 'review']);
        return view('transactions.show', compact('transaction'));
    }

    // Buyer clicks "Buy Now" — create transaction and show payment page
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        if ($listing->status !== 'active') {
            return back()->with('error', 'This listing is no longer available.');
        }

        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot buy your own listing.');
        }

        // Check no active transaction for this listing
        $existing = Transaction::where('listing_id', $listing->id)
            ->where('buyer_id', auth()->id())
            ->whereIn('status', ['pending', 'paid', 'escrow'])
            ->first();

        if ($existing) {
            return redirect()
                ->route('transactions.show', $existing)
                ->with('error', 'You already have an active transaction for this listing.');
        }

        // Calculate fees
        $fee    = round($listing->price * 0.05, 2);
        $payout = round($listing->price - $fee, 2);

        // Create transaction with pending_payment status
        $transaction = Transaction::create([
            'transaction_code'  => Transaction::generateCode(),
            'listing_id'        => $listing->id,
            'buyer_id'          => auth()->id(),
            'seller_id'         => $listing->user_id,
            'amount'            => $listing->price,
            'platform_fee'      => $fee,
            'seller_payout'     => $payout,
            'payment_method'    => 'bank_transfer',
            'status'            => 'pending',
        ]);

        // Reserve the listing so others can't buy it
        $listing->update(['status' => 'reserved']);

        return redirect()
            ->route('transactions.payment', $transaction);
    }

    // Show payment instructions page
    public function payment(Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction);
        }

        $transaction->load(['listing.game', 'seller']);
        $bank = config('payment');

        return view('transactions.payment', compact('transaction', 'bank'));
    }

    // Buyer clicks "I Have Paid"
    public function markPaid(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'This transaction cannot be updated.');
        }

        $request->validate([
            'payment_note' => 'nullable|string|max:500',
        ]);

        $transaction->update([
            'status'       => 'paid',
            'buyer_paid_at'=> now(),
            'payment_note' => $request->payment_note,
        ]);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', '✅ Payment marked! Admin will verify within 1-3 hours.');
    }

    // Buyer confirms receipt — release escrow to seller
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
            ->with('success', '🎉 Payment released to seller. Thank you!');
    }

    // Buyer raises dispute
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

    // Buyer cancels before paying
    public function cancel(Transaction $transaction)
    {
        if ($transaction->buyer_id !== auth()->id()) {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Cannot cancel this transaction.');
        }

        // Release listing back to active
        $transaction->listing->update(['status' => 'active']);
        $transaction->update(['status' => 'cancelled']);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Order cancelled. Listing is available again.');
    }
}
