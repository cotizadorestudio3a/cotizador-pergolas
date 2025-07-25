<?php

namespace App\Livewire\Vendor\Quotations;

use App\Models\Quotation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ViewModal extends Component
{
    public ?Quotation $quotation = null;
    public bool $showModal = false;

    protected $listeners = ['openViewModal'];

    public function openViewModal($quotationId)
    {
        $this->quotation = Quotation::with([
            'client',
            'quotationItems.service',
            'quotationItems.serviceVariant',
            'pdfs.quotationItem.service',
            'pdfs.quotationItem.serviceVariant'
        ])
        ->where('user_id', Auth::id())
        ->find($quotationId);

        if ($this->quotation) {
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->quotation = null;
    }

    public function render()
    {
        return view('livewire.vendor.quotations.view-modal');
    }
}
