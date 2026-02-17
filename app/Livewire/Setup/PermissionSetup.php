<?php

namespace App\Livewire\Setup;

use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;

class PermissionSetup extends Component
{
    use Toast;
    public bool $drawer = false;
    public string $package = '';
    public $actions = [];
    public $title = 'Create Permission';
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function updatedPackage($value)
    {
        if (trim($value) === '') {
            return;
        }


        $this->actions = [
            "{$value}-create",
            "{$value}-read",
            "{$value}-edit",
            "{$value}-delete",
        ];
    }

    public function savePermission()
    {
        $this->validate([
            'package' => 'required|string|unique:permissions,package_name',
            'actions' => 'required|array|min:1',
            'actions.*' => 'required|string|distinct',
        ]);

        try {
            foreach ($this->actions as $action) {
                $is_saved = Permission::create([
                    'name' => $action,
                    'package_name' => $this->package,
                    'guard_name' => 'web',
                ]);
            }

            if (!$is_saved) {
                $this->error('Failed to create permissions', position: 'toast-bottom');
                return false;
            } else {
                $this->success('Permissions created successfully', position: 'toast-bottom');
                $this->drawer = false;
                $this->package = '';
                $this->actions = [];
            }


        } catch (\Exception $e) {
            $this->error('Failed to create permissions', position: 'toast-bottom');

            return;
        }
    }
    public function render()
    {
        return view('livewire.setup.permission-setup', [
            'permission' => $this->permissionData(),
            'headers' => $this->headers(),
        ]);
    }

    public function permissionData()
    {
        return Permission::query()
            ->selectRaw('id, name, package_name, created_at, updated_at')
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function headers()
    {
        return [
            ['key' => 'id', 'label' => 'Id',],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-50',],
            ['key' => 'package_name', 'label' => 'Package name', 'sortable' => false],
            ['key' => 'created_at', 'label' => 'Created At', 'sortable' => false],
            ['key' => 'updated_at', 'label' => 'Updated At', 'sortable' => false],
        ];
    }
}
