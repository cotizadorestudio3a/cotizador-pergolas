<?php

namespace App\Livewire\Admin\Vendors;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Delete extends Component
{
    public $user;

    public function delete()
    {
        try {
            $userId = $this->user->id;
            $this->user->delete();

            // Cierra el modal de Flux
            Flux::modals()->close();

            $this->dispatch('vendor-deleted', name: $this->user->name);
            $this->dispatch('refresh'); // Forzar una actualización del componente padre

        } catch (\Exception $e) {
            Flux::modals()->close();
            Log::error('Error al eliminar usuario: '.$e->getMessage());

            $this->dispatch('error-occurred', message: 'Ocurrió un error al eliminar el usuario.');
        }
    }
}
