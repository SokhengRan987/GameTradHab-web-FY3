<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TransactionController extends Controller
{
    public function __construct(
        private EscrowService $escrowService,
    ) {}

    // ── My Orders page ────────────────────────────────────────────────────────
    public function index()
    {
        $purchases = Transaction::with(['listing', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->paginate(10, ['*'], 'purchases_page');

        $sales = Transaction::with(['listing', 'buyer'])
            ->where('seller_id', Auth::id())
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        return view('transactions.index', compact('purchases', 'sales'));
    }

    // ── Single transaction detail ─────────────────────────────────────────────
    public function show(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id() && $transaction->seller_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['listing', 'buyer', 'seller', 'escrowLog', 'review']);

        return view('transactions.show', compact('transaction'));
    }

    // ── Step 1: Buyer clicks "Buy Now" — create pending transaction ───────────
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        if ($listing->status !== 'active') {
            return back()->with('error', 'This listing is no longer available.');
        }

        if ($listing->user_id === Auth::id()) {
            return back()->with('error', 'You cannot buy your own listing.');
        }

        // Prevent duplicate transactions
        $existing = Transaction::where('listing_id', $listing->id)
            ->where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'paid', 'escrow'])
            ->first();

        if ($existing) {
            return redirect()
                ->route('transactions.payment', $existing)
                ->with('info', 'You already have an active order for this listing.');
        }

        // Calculate fees
        $fee    = round($listing->price * 0.05, 2);
        $payout = round($listing->price - $fee, 2);

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'listing_id'       => $listing->id,
            'buyer_id'         => Auth::id(),
            'seller_id'        => $listing->user_id,
            'amount'           => $listing->price,
            'platform_fee'     => $fee,
            'seller_payout'    => $payout,
            'payment_method'   => 'card',
            'status'           => 'pending',
        ]);

        // Reserve listing so nobody else can buy it
        $listing->update(['status' => 'reserved']);

        return redirect()->route('transactions.payment', $transaction);
    }

    // ── Step 2: Checkout summary page (payment method selection) ─────────────
    public function payment(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction);
        }

        $transaction->load(['listing.game', 'seller']);

        return view('transactions.payment', compact('transaction'));
    }

    // ── Step 3: Card details form ─────────────────────────────────────────────
    public function card(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction);
        }

        $transaction->load(['listing.game', 'seller']);

        return view('transactions.card', compact('transaction'));
    }

    // ── Step 4: Process card payment (fake — for demo) ────────────────────────
    public function pay(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.show', $transaction);
        }

        $request->validate([
            'card_number' => 'required|string',
            'expiry'      => 'required|string',
            'cvv'         => 'required|string|min:3',
            'card_name'   => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'zip'         => 'required|string',
        ]);

        $last4 = substr(str_replace(' ', '', $request->card_number), -4);

        $transaction->update([
            'status'         => 'paid',
            'buyer_paid_at'  => now(),
            'payment_method' => 'card',
            'payment_note'   => 'Paid by card ending ' . $last4,
        ]);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', '🎉 Payment successful! Funds held in escrow. Contact the seller now!');
    }

    // ── Buyer confirms receipt — release escrow to seller ─────────────────────
    public function confirm(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'escrow') {
            return back()->with('error', 'This transaction cannot be confirmed.');
        }

        $this->escrowService->release($transaction, 'buyer');

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', '✅ Payment released to seller. Thank you!');
    }

    // ── Buyer raises dispute ──────────────────────────────────────────────────
    public function dispute(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'escrow') {
            return back()->with('error', 'Cannot raise dispute on this transaction.');
        }

        $transaction->update(['status' => 'disputed']);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Dispute raised. Admin will review within 24-48 hours.');
    }

    // ── Buyer cancels before paying ───────────────────────────────────────────
    public function cancel(Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Cannot cancel this transaction.');
        }

        $transaction->listing->update(['status' => 'active']);
        $transaction->update(['status' => 'cancelled']);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Order cancelled. Listing is available again.');
    }

    // ── Mark paid manually (kept for admin use) ───────────────────────────────
    public function markPaid(Request $request, Transaction $transaction)
    {
        if ($transaction->buyer_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'This transaction cannot be updated.');
        }

        $transaction->update([
            'status'        => 'paid',
            'buyer_paid_at' => now(),
            'payment_note'  => $request->payment_note,
        ]);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', 'Payment marked! Admin will verify within 1-3 hours.');
    }
}
