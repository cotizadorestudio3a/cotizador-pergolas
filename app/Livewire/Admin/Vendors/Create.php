<?php

namespace App\Livewire\Admin\Vendors;

use App\Models\User;
use App\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VendorCredentials;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'lowercase', 'email', 'unique:' . User::class],
        ], 
            [
                'email.unique' => 'El correo electrónico ya está en uso por otro usuario.',
                'email.lowercase' => 'El correo electrónico debe estar en minúsculas.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            ]
    );

        // Generar contraseña aleatoria
        $plainPassword = Str::random(10);
        $validated['password'] = Hash::make($plainPassword);
        $validated['role_id'] = UserRole::Vendedor->value;

        event(new Registered(($user = User::create($validated))));

        // Enviar correo con las credenciales
        Mail::to($user->email)->send(new VendorCredentials($user, $plainPassword));

        $this->modal('create')->close();

        //reset inputs in modal
        $this->reset('name', 'email');

        $this->dispatch('vendor-created', name: $user->name);
    }
}
