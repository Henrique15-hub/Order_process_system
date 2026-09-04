<?php

namespace App\Listeners\Client;

use App\Events\Client\ClientCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repository\CartRepository;

class CreateCart
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private CartRepository $repo,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClientCreated $event): void
    {
        $this->repo->store([
            "client_id" => $event->client->id,
            "items" => [],
            "actual_value" => 0
        ]);
    }
}