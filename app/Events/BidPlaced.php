<?php

namespace App\Events;

use App\Models\Listing;
use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class BidPlaced implements ShouldBroadcastNow
{
    public Listing $listing;
    public Bid $bid;

    public function __construct(Listing $listing, Bid $bid)
    {
        $this->listing = $listing;
        $this->bid = $bid;
    }

    public function broadcastOn()
    {
        return [
            new Channel('auction.' . $this->listing->id)
        ];
    }

    public function broadcastAs()
    {
        return 'BidPlaced';
    }

    public function broadcastWith()
    {
        return [
            'bid' => [
                'amount' => $this->bid->amount,
                'user' => [
                    'id' => $this->bid->user->id,   // ✅ ADD THIS
                    'name' => $this->bid->user->name,
                ],
            ],
            'listing_id' => $this->listing->id,
        ];
    }
}
