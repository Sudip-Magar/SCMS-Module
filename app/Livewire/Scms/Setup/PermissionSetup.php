<?php

namespace App\Livewire\Scms\Setup;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;

class PermissionSetup extends Component
{
    use Toast;
    public bool $drawer = false;

    public $id;
    public string $package_name = '';
    public string $sub_package_name = '';
    public array $actions = [
        'read' => true,
        'create' => true,
        'edit' => true,
        'delete' => true,
    ];

    public array $packages = [];
    public array $subPackages = [];
    public $title = 'Create Permission';
    public array $sortBy = ['column' => 'package_name', 'direction' => 'asc'];

    public function mount()
    {
        $this->packages = Permission::select('package_name')
            ->distinct()
            ->pluck('package_name')
            ->toArray();
    }

    public function updatedPackageName()
    {
        $this->package_name = strtolower(trim($this->package_name));

        $slug = Str::slug($this->package_name, '_');

        $this->subPackages = Permission::where('package_name', $slug)
            ->distinct()
            ->pluck('sub_package_name')
            ->toArray();
    }

    public function getPreviewPermissionsProperty()
    {
        $list = [];

        foreach ($this->actions as $action => $checked) {
            if ($checked) {
                $list[] = "{$this->package_name}-{$this->sub_package_name}-{$action}";
            }
        }

        return $list;
    }

    public function savePermission()
    {
        $this->validate([
            'package_name' => 'required|string',
            'sub_package_name' => 'required|string',
        ]);

        $package = Str::slug($this->package_name, '_');
        $sub = Str::slug($this->sub_package_name, '_');

        try {

            foreach ($this->actions as $action => $checked) {
                if (!$checked)
                    continue;

                $name = "{$package}-{$sub}-{$action}";

                if ($this->id) {
                    $this->validate([
                        'package_name' => 'required|string',
                        'sub_package_name' => 'required|string',

                    ]);

                    $permission = Permission::find($this->id);
                    if ($permission) {
                        $permission->update([
                            // 'name' => $name,
                            'package_name' => $package,
                            'sub_package_name' => $sub,
                            'guard_name' => 'web',
                        ]);
                    }
                } else {
                    // Create new permission
                    Permission::firstOrCreate([
                        'name' => $name,
                        'package_name' => $package,
                        'sub_package_name' => $sub,
                        'guard_name' => 'web',
                    ]);
                }
            }

            $this->success('Permissions saved successfully', position: 'toast-bottom');

            // Reset form
            $this->resetForm();

            $this->drawer = false;

        } catch (\Exception $e) {
            $this->error("Failed to save permissions: " . $e->getMessage(), position: 'toast-bottom');
            return false;
        }
    }

    public function render()
    {
        return view('livewire.scms.setup.permission-setup', [
            'permissions' => $this->permissionData(),
            'headers' => $this->headers(),
        ]);
    }

    public function permissionData()
    {
        return Permission::query()
            ->selectRaw('id, name, package_name, sub_package_name, created_at, updated_at')
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }
    public function edit(Permission $permission)
    {
        $this->resetFormValidation();
        $this->id = $permission->id;
        $this->package_name = $permission->package_name;
        $this->sub_package_name = $permission->sub_package_name;

        $actions = explode('-', $permission->name);
        $action = end($actions);

        foreach ($this->actions as $key => &$value) {
            $value = ($key === $action);
        }

        $this->drawer = true;
        $this->title = 'Edit Permission';
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'package_name', 'label' => 'Package name', 'class' => 'w-50'],
            ['key' => 'sub_package_name', 'label' => 'Sub package name', 'sortable' => false],
            ['key' => 'name', 'label' => 'Acceess', 'sortable' => false],
            // ['key' => 'created_at', 'label' => 'Created At', 'sortable' => false],
            // ['key' => 'updated_at', 'label' => 'Updated At', 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'package_name',
            'sub_package_name',
            'id',
        ]);
    }

    public function resetFormValidation()
    {
        $this->resetValidation();
    }

}
