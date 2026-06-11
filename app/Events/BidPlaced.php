<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class BidPlaced implements ShouldBroadcastNow
{
    public $listing;
    public $bid;

    public function __construct($listing, $bid)
    {
        $this->listing = $listing;
        $this->bid = $bid;
    }

    public function broadcastOn()
    {
        return new Channel('auction.' . $this->listing->id);
    }

    public function broadcastAs()
    {
        return 'BidPlaced';
    }
}
