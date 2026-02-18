<?php

namespace App\Livewire\Setup\Role;

use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    use Toast;
    public $id;
    public string $name = '';
    public array $selectedPermissions = [];

    public function mount($id = null)
    {
        $this->id = $id;

        if ($id) {
            $role = Role::findOrFail($id);

            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->toArray();
        }
        // dd($this->selectedPermissions);
    }


    public function groupedPermissions()
    {
        $permissions = Permission::all();

        $data = [];

        foreach ($permissions as $perm) {

            $package = $perm->package_name;
            $sub = $perm->sub_package_name;

            $parts = explode('-', $perm->name);
            $action = end($parts);

            $data[$package][$sub][$action] = $perm->id;
        }

        return $data;
    }

    public function saveRole()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->id,
        ]);

        // dd($this->selectedPermissions);
        $role = Role::updateOrCreate(
            ['id' => $this->id],
            ['name' => $this->name]
        );

        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

        // Sync permissions correctly
        $role->syncPermissions($permissions);

        $this->success(  
            title: 'Role Saved Successfully',
            description: null,                  // optional (text)
            position: 'toast-bottom',    // optional (daisyUI classes)
            icon: 'o-check-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('setup.role')      // optional redirect
        );
    }



    public function render()
    {
        return view('livewire.setup.role.create-role', [
            'grouped' => $this->groupedPermissions(),
        ]);
    }
}
