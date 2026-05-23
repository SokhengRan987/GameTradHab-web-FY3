<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private EscrowService $escrowService,
    ) {}

    // Show all transactions
    public function index(Request $request)
    {
        $transactions = Transaction::with(['listing', 'buyer', 'seller'])
            ->when($request->status,
                fn($q) => $q->where('status', $request->status),
                fn($q) => $q->where('status', 'paid') // default show pending payments
            )
            ->latest()
            ->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }

    // Admin confirms payment received -> activates releaseEscrow
    public function confirmPayment(Transaction $transaction)
    {
        if ($transaction->status !== 'paid') {
            return back()->with('error', 'This transaction is not awaiting payment confirmation.');
        }

        // Activate escrow
        $this->escrowService->hold($transaction);

        $transaction->update([
            'admin_confirmed_at' => now(),
            'review_deadline' => now()->addHours(48),
        ]);

        return back()->with('success', 'Payment confirmed. Escrow activated. Seller notified.');
    }

    // Admin releases escrow to seller (dispute resolved)
    public function releaseEscrow(Transaction $transaction)
    {
        if (!in_array($transaction->status, ['escrow', 'disputed'])) {
            return back()->with('error', 'Cannot release this transaction.');
        }

        $this->escrowService->release($transaction, 'admin');

        return back()->with('success', 'Escrow released. Seller has been paid.');
    }

    // Admin refunds buyer
    public function refund(Transaction $transaction) {
        if ($transaction->status !== 'disputed') {
            return back()->with('error', 'Can only refund disputed transactions.');
        }

        $this->escrowService->refund($transaction);

        // Release listing back to active
        $transaction->listing->update(['status' => 'active']);

        return back()->with('success','Buyer refunded successfully.');
    }
}
