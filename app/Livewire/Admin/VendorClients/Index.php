<?php

namespace App\Livewire\Admin\VendorClients;

use App\Models\User;
use App\UserRole;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $users = User::where('role_id', UserRole::Vendedor->value)->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.vendor-clients.index', compact('users'));
    }
}
