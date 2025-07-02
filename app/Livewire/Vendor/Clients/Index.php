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
            $client->delete();

            $this->dispatch('client-deleted', name: $client->name);
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente: '. $e->getMessage());

            $this->dispatch('error-occurred', message: 'Ocurrió un error al eliminar el cliente.');
        }
    }
    public function render()
    {
        $clients = auth()->user()->clients()->paginate(10);
        return view('livewire.vendor.clients.index', compact('clients'));
    }
}
