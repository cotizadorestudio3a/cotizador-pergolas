<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Client;
use App\Models\User;
use App\Models\Quotation;
use App\UserRole;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        // Contar todos los usuarios
        $clientCount = Client::count();
        
        // Contar vendedores específicamente
        $vendorsCount = User::where('role_id', UserRole::Vendedor->value)->count();
        
        // Contar todas las cotizaciones
        $quotationsCount = Quotation::count();
        
        // Obtener vendedores recientes para la tabla
        $users = User::where('role_id', UserRole::Vendedor->value)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
        
        return view('livewire.admin.dashboard.overview', [
            'clientCount' => $clientCount,
            'vendorsCount' => $vendorsCount,
            'quotationsCount' => $quotationsCount,
            'users' => $users
        ]);
    }
}
