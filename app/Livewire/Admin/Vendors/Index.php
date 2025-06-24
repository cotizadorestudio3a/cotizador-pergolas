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
            $user->delete();

            $this->dispatch('vendor-deleted', name: $user->name);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());

            $this->dispatch('error-occurred', message: 'Ocurrió un error al eliminar el usuario.');
        }
    }

}
