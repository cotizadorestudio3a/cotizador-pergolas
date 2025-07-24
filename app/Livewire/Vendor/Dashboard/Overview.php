<?php

namespace App\Livewire\Vendor\Dashboard;

use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $clients = auth()->user()->clients()->paginate(10);
        $quotationTotalAmount = auth()->user()->quotations()->sum('total');
        return view('livewire.vendor.dashboard.overview', compact('clients', 'quotationTotalAmount'));
    }
}
