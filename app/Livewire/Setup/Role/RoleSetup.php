<?php

namespace App\Livewire\Setup\Role;


use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class RoleSetup extends Component
{
    use Toast;
    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function createRole()
    {
        try {
            Role::create([
                'name' => $this->name,
                'guard_name' => 'web',
            ]);
            $this->success('Role created successfully', position: 'toast-bottom');
            $this->drawer = false;
            $this->name = '';


        } catch (\Exception $e) {
            $this->error('Failed to create role', position: 'toast-bottom');
            return;
        }
    }

    public function RoleData()
    {
        return Role::query()
            ->selectRaw('id, name, created_at, updated_at')
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-50'],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => false],
            ['key' => 'updated_at', 'label' => 'Updated At', 'sortable' => false],
        ];
    }
    public function render()
    {
        return view('livewire.setup.role.role-setup', [
            'roles' => $this->RoleData(),
            'headers' => $this->headers(),
        ]);
    }
}
