<?php

namespace App\Livewire\Vendor\Clients;

use App\Models\Client;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Index extends Component
{

    public function delete(Client $client)
    {
        try {
            // Verificar si el cliente tiene cotizaciones asociadas
            if ($client->quotations()->exists()) {
                $this->dispatch('error-occurred', message: 'No se puede eliminar el cliente porque tiene cotizaciones asociadas. Elimina primero las cotizaciones relacionadas.');
                return;
            }
            
            $client->vendors()->detach(); // Desvincular clientes de vendedores
            $client->delete();
            $this->dispatch('success-info-message', message: 'Cliente eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente: ' . $e->getMessage());
            $this->dispatch('error-occurred', message: 'Ocurrió un error al eliminar el cliente. Asegúrate de que no tenga cotizaciones asociadas.');
        }
    }
    public function render()
    {
        $clients = auth()->user()->clients()->paginate(10);
        return view('livewire.vendor.clients.index', compact('clients'));
    }
}
