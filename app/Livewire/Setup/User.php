<?php

namespace App\Livewire\Setup;

use Livewire\Component;
use App\Models\User as UserModel;
use Mary\Traits\Toast;

class User extends Component
{
    use Toast;
    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function render()
    {
        return view('livewire.setup.user', [
            'users' => $this->userData(),
            'headers' => $this->headers(),
        ]);
    }

    public function userData()
    {
        return UserModel::query()
            ->with('roles')
            ->select('id', 'name', 'email', 'created_at', 'updated_at')
            ->orderBy(...array_values($this->sortBy))
            ->get()
            ->map(fn($user) => [
                // 'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->join(', '),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-50'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'w-50'],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => false],
            ['key' => 'updated_at', 'label' => 'Updated At', 'sortable' => false],
        ];
    }
}
