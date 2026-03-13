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
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }

    // Admin releases escrow manually (e.g. after dispute resolved)
    public function releaseEscrow(Transaction $transaction)
    {
        if ($transaction->status !== 'escrow' && $transaction->status !== 'disputed') {
            return back()->with('error', 'Cannot release this transaction.');
        }

        $this->escrowService->release($transaction, 'admin');

        return back()->with('success', 'Escrow released. Seller has been paid.');
    }

    // Admin refunds buyer after dispute
    public function refund(Transaction $transaction)
    {
        if ($transaction->status !== 'disputed') {
            return back()->with('error', 'Can only refund disputed transactions.');
        }

        $this->escrowService->refund($transaction);

        return back()->with('success', 'Buyer refunded successfully.');
    }
}
