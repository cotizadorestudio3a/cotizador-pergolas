<?php

namespace App\Livewire\Admin\Vendors;

use App\Models\User;
use App\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function save()
    {
        $validated = $this->validate( [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'lowercase', 'email', 'unique:'.User::class],
        ]);

        $validated['password'] = Hash::make('hola12345');
        $validated['role_id'] = UserRole::Vendedor->value;

        event(new Registered(($user = User::create($validated))));

        $this->modal('create')->close();
        
        //reset inputs in modal
        $this->reset('name', 'email');

        $this->dispatch('vendor-created', name: $user->name );
    }
}
