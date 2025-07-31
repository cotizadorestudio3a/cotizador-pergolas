<?php

namespace App\Livewire\Admin\Vendors;

use App\Models\User;
use App\UserRole;
use Flux\Flux;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{

    public function render()
    {
        $users = User::where('role_id',UserRole::Vendedor->value)->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.vendors.index', compact('users'))->title('Vendedores');
    }

    public function delete(User $user)
    {
        try {
            // Verificar si el vendedor tiene cotizaciones relacionadas
            $quotationsCount = $user->quotations()->count();
            
            if ($quotationsCount > 0) {
                $this->dispatch('error-occurred', 
                    message: "No se puede eliminar el vendedor {$user->name}. Tiene {$quotationsCount} cotización(es) asociada(s). Debe eliminar las cotizaciones primero."
                );
                return;
            }

            // Verificar si tiene clientes asignados
            $clientsCount = $user->clients()->count();
            
            if ($clientsCount > 0) {
                $this->dispatch('error-occurred', 
                    message: "No se puede eliminar el vendedor {$user->name}. Tiene {$clientsCount} cliente(s) asignado(s). Debe reasignar los clientes primero."
                );
                return;
            }

            // Si no tiene relaciones, proceder con la eliminación
            $userName = $user->name;
            $user->delete();

            $this->dispatch('vendor-deleted', name: $userName);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar vendedor: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'error' => $e->getTrace()
            ]);

            $this->dispatch('error-occurred', 
                message: 'Ocurrió un error inesperado al eliminar el vendedor. Por favor, inténtelo nuevamente.'
            );
        }
    }

    /**
     * Obtiene información detallada sobre las dependencias de un vendedor
     */
    public function getVendorDependencies(User $user)
    {
        $quotations = $user->quotations()->with('client')->get();
        $clients = $user->clients()->get();
        
        $this->dispatch('show-vendor-dependencies', [
            'vendor_name' => $user->name,
            'quotations_count' => $quotations->count(),
            'clients_count' => $clients->count(),
            'quotations' => $quotations->map(function($q) {
                return [
                    'id' => $q->id,
                    'client_name' => $q->client->name ?? 'Cliente no disponible',
                    'created_at' => $q->created_at->format('d/m/Y H:i')
                ];
            }),
            'clients' => $clients->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'email' => $c->email
                ];
            })
        ]);
    }

}
