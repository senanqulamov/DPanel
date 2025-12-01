<?php

namespace App\Events;

use App\Models\Quote;
use App\Models\Request;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The request instance.
     *
     * @var \App\Models\Request
     */
    public $request;

    /**
     * The quote instance.
     *
     * @var \App\Models\Quote
     */
    public $quote;

    /**
     * The supplier user instance.
     *
     * @var \App\Models\User
     */
    public $supplier;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Quote  $quote
     * @return void
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
        $this->request = $quote->request;
        $this->supplier = $quote->supplier;
    }
}
