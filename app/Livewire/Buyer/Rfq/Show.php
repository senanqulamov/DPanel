<?php

namespace App\Livewire\Buyer\Rfq;

use App\Livewire\Traits\Alert;
use App\Livewire\Traits\WithLogging;
use App\Models\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use Alert, WithLogging;

    public Request $request;

    public function mount(Request $request): void
    {
        $this->request = $request->load([
            'buyer',
            'items.product',
            'quotes.supplier',
            'quotes.items.requestItem.product',
            'supplierInvitations.supplier',
        ]);

        // Ensure the buyer owns this RFQ
        if ($this->request->buyer_id !== Auth::id()) {
            abort(403, 'You do not have permission to view this RFQ.');
        }

        $this->logPageView('Buyer RFQ Show', [
            'request_id' => $this->request->id,
        ]);
    }

    public function render(): View
    {
        return view('livewire.buyer.rfq.show');
    }
}
