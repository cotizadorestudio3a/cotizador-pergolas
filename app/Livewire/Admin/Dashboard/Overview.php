<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\User;
use App\UserRole;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $vendorsCount = User::where('role_id', UserRole::Vendedor->value)->count();
        $users = User::where('role_id', UserRole::Vendedor->value)->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.dashboard.overview',
        [
            "vendorsCount" => $vendorsCount,
            "users" => $users
        ]);
    }
}
