
<?php
/*
namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Transaction;
use App\Services\EscrowService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private EscrowService $escrowService,
    ) {}

    // Step 1 — Show checkout summary page
    // GET /checkout/{listing}
    public function show(Listing $listing)
    {
        if ($listing->status !== 'active' || $listing->type !== 'fixed') {
            return redirect()->route('listings.show', $listing)
                ->with('error', 'This listing is not available.');
        }

        if ($listing->user_id === auth()->id()) {
            return redirect()->route('listings.show', $listing)
                ->with('error', 'You cannot buy your own listing.');
        }

        $fee = round($listing->price * 0.05, 2);

        return view('transaction.payment', compact('listing', 'fee'));
    }

    // Step 2 — Show card details page
    // GET /checkout/{listing}/card
    public function card(Listing $listing)
    {
        if ($listing->status !== 'active') {
            return redirect()->route('listings.show', $listing)
                ->with('error', 'This listing is not available.');
        }

        $fee = round($listing->price * 0.05, 2);

        return view('checkout.card', compact('listing', 'fee'));
    }

    // Process payment and create transaction
    // POST /checkout/{listing}/pay
    public function pay(Request $request, Listing $listing)
    {
        // Validate card fields
        $request->validate([
            'card_number' => 'required|string',
            'expiry'      => 'required|string',
            'cvv'         => 'required|string|min:3|max:4',
            'card_name'   => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'zip'         => 'required|string',
            'country'     => 'required|string',
        ]);

        if ($listing->status !== 'active') {
            return back()->with('error', 'This listing is no longer available.');
        }

        if ($listing->user_id === auth()->id()) {
            return back()->with('error', 'You cannot buy your own listing.');
        }

        // Check for existing transaction
        $existing = Transaction::where('listing_id', $listing->id)
            ->where('buyer_id', auth()->id())
            ->whereIn('status', ['pending', 'escrow'])
            ->first();

        if ($existing) {
            return redirect()->route('transactions.show', $existing)
                ->with('error', 'You already have an active transaction for this listing.');
        }

        // Calculate fees
        $fee    = round($listing->price * 0.05, 2);
        $payout = round($listing->price - $fee, 2);

        // For demo: credit the buyer's wallet with the card amount, then deduct via escrow
        // In production, replace this with a real Stripe charge
        $this->walletService->credit(
            userId:      auth()->id(),
            amount:      $listing->price,
            type:        'card_payment',
            description: 'Card payment for: ' . $listing->title
        );

        // Create transaction
        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'listing_id'       => $listing->id,
            'buyer_id'         => auth()->id(),
            'seller_id'        => $listing->user_id,
            'amount'           => $listing->price,
            'platform_fee'     => $fee,
            'seller_payout'    => $payout,
            'payment_method'   => 'card',
        ]);

        // Hold funds in escrow
        $this->escrowService->hold($transaction);

        return redirect()
            ->route('transactions.show', $transaction)
            ->with('success', '🎉 Payment successful! Your funds are held in escrow.');
    }
}
*/
