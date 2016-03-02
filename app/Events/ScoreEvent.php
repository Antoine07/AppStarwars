<?php

namespace App\Events;

use App\Score;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ScoreEvent extends Event
{
    use SerializesModels;

    public $productId;
    public $quantity;

    /**
     * ScoreEvent constructor.
     * @param $productId
     * @param $quantity
     */
    public function __construct($productId, $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
