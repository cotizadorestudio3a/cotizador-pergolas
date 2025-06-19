<?php

namespace App\Livewire\Admin\Vendors;

use App\Models\User;
use App\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public int $id;
    public string $name = '';

    public string $email = '';
    public bool $status;

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

        session()->flash('success', 'Se creó el vendedor ' . $user->name );
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->modal("delete-vendor-$user->id")->close();
        session()->flash('success', 'Se eliminó el vendedor ' . $user->name );

    }

    public function mount(User $user)
    {
        $this->status = (bool) $user->status;
    }

    public function render()
    {
        $users = User::where('role_id',UserRole::Vendedor->value)->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.vendors.index', compact('users'));
    }
}
