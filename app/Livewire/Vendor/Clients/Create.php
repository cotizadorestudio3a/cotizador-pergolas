<?php

namespace App\Livewire\Vendor\Clients;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class Create extends Component
{

    public array $provinces = [
        'Azuay',
        'Bolívar',
        'Cañar',
        'Carchi',
        'Chimborazo',
        'Cotopaxi',
        'El Oro',
        'Esmeraldas',
        'Galápagos',
        'Guayas',
        'Imbabura',
        'Loja',
        'Los Ríos',
        'Manabí',
        'Morona Santiago',
        'Napo',
        'Orellana',
        'Pastaza',
        'Pichincha',
        'Santa Elena',
        'Santo Domingo de los Tsáchilas',
        'Sucumbíos',
        'Tungurahua',
        'Zamora Chinchipe',
    ];

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|int|unique:clients,dni')]
    public $dni;

    #[Validate('required|string|max:255')]
    public $province;

    #[Validate('required|int|unique:clients,phone')]
    public $phone;

    public function save()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $client = Client::create($validated);

            $client->vendors()->attach(auth()->id());

            $this->dispatch('client-created', name: $client->name);
        });

        $this->modal('create')->close();

        $this->reset('name', 'dni', 'province', 'phone');
    }


    public function render()
    {
        return view('livewire.vendor.clients.create', [
            'provinces' => $this->provinces,
        ]);
    }
}
