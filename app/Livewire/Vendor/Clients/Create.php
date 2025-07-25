<?php

namespace App\Livewire\Vendor\Clients;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    #[Validate('required|string|min:2|max:50')]
    public $name;

    #[Validate('required|string|size:10|unique:clients,dni')]
    public $dni;

    #[Validate('required|string|max:255')]
    public $province;

    #[Validate('required|string|min:9|max:10|unique:clients,phone')]
    public $phone;

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede exceder 50 caracteres.',
            
            'dni.required' => 'La cédula es obligatoria.',
            'dni.size' => 'La cédula debe tener exactamente 10 dígitos.',
            'dni.unique' => 'Ya existe un cliente registrado con esta cédula.',
            
            'province.required' => 'Debe seleccionar una provincia.',
            
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.min' => 'El teléfono debe tener al menos 9 dígitos.',
            'phone.max' => 'El teléfono no puede tener más de 10 dígitos.',
            'phone.unique' => 'Ya existe un cliente registrado con este teléfono.',
        ];
    }

    /**
     * Validate Ecuadorian format in real time
     */
    public function updated($propertyName)
    {
        // Clean DNI and phone to only numbers
        if ($propertyName === 'dni') {
            $this->dni = preg_replace('/[^0-9]/', '', $this->dni);
            
            // Basic Ecuador cedula validation (starts with valid province code)
            if (strlen($this->dni) >= 2) {
                $province = substr($this->dni, 0, 2);
                if ($province < 1 || $province > 24) {
                    $this->addError('dni', 'La cédula debe comenzar con un código de provincia válido (01-24).');
                    return;
                }
            }
        }
        
        if ($propertyName === 'phone') {
            $this->phone = preg_replace('/[^0-9]/', '', $this->phone);
            
            // Basic Ecuador phone validation
            if (strlen($this->phone) > 0 && !str_starts_with($this->phone, '0')) {
                $this->addError('phone', 'El teléfono debe comenzar con 0 (ejemplo: 0987654321 o 022345678).');
                return;
            }
            
            // Check if it's mobile or landline
            if (strlen($this->phone) >= 2) {
                $prefix = substr($this->phone, 0, 2);
                if ($prefix !== '09' && !in_array($prefix, ['02', '03', '04', '05', '06', '07'])) {
                    $this->addError('phone', 'Número inválido. Use: 09XXXXXXXX (celular) o 0X-XXXXXXX (fijo).');
                    return;
                }
            }
        }
        
        // Validate the specific field
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            // Clean data before saving
            $validated['dni'] = preg_replace('/[^0-9]/', '', $validated['dni']);
            $validated['phone'] = preg_replace('/[^0-9]/', '', $validated['phone']);
            $validated['name'] = ucwords(strtolower(trim($validated['name'])));
            
            $client = Client::create($validated);

            $client->vendors()->attach(Auth::id());

            $this->dispatch('client-created', message: 'Cliente creado exitosamente');
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
