<?php

namespace App\Livewire\Admin\VendorClients;

use App\Models\Client;
use App\Models\User;
use App\UserRole;
use Livewire\Component;
use Livewire\WithPagination;

class Assign extends Component
{
    use WithPagination;

    public User $vendedor;
    public array $selectedClients = [];
    public $query = '';

    public function mount(User $vendedor)
    {
        $this->vendedor = $vendedor;
        $this->selectedClients = $vendedor->clients()->pluck('clients.id')->toArray();
    }

    public function save()
    {
        $this->vendedor->clients()->sync($this->selectedClients);

        session()->flash('success', 'Clientes asignados correctamente.');
        return redirect()->route('admin.assign.index');
    }

    public function removeClient($clientId)
    {
        // Remover el cliente de los seleccionados
        $this->selectedClients = array_diff($this->selectedClients, [$clientId]);

        // Si quieres que se actualice inmediatamente sin esperar al save()
        $this->vendedor->clients()->detach($clientId);
        $this->selectedClients = $this->vendedor->clients()->pluck('clients.id')->toArray();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function getClientesAsignadosProperty()
    {
        return $this->vendedor->clients()
            ->when($this->query, function($query) {
                $query->where('name', 'like', '%'.$this->query.'%');
            })
            ->get();
    }

    public function getClientesDisponiblesProperty()
    {
        return Client::whereNotIn('id', $this->clientesAsignados->pluck('id'))
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->query.'%');
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.vendor-clients.assign',  [
            'clientesAsignados' => $this->clientesAsignados,
            'clientesDisponibles' => Client::where('name', 'like', '%'.$this->query.'%')->paginate(10),
        ])->title('Asignar clientes');
    }

}
