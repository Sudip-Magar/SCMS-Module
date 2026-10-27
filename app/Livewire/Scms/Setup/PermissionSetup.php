<?php

namespace App\Livewire\Scms\Setup;

use App\Traits\WithCustomPagination;
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;

class PermissionSetup extends Component
{
    use Toast, WithCustomPagination;

    public bool $drawer = false;

    public $id;
    public string $package_name = '';
    public string $sub_package_name = '';
    public array $actions = [
        'list' => true,
        'create' => true,
        'edit' => true,
        'delete' => true,
    ];

    public array $packages = [];
    public array $subPackages = [];
    public $title = 'Create Permission';
    public array $sortBy = ['column' => 'package_name', 'direction' => 'asc'];
    public $search = '';

    public $allowedPermissions = [
        'list' => false,
        'create' => false,
        'edit' => false,
        'delete' => false,
    ];


    public function mount()
    {
        $this->allowedPermissions = [
            'list' => authorizeUserCheck('setup-permission-list'),
            'create' => authorizeUserCheck('setup-permission-create'),
            'edit' => authorizeUserCheck('setup-permission-edit'),
            'delete' => authorizeUserCheck('setup-permission-delete'),
        ];

        authorizeUserModal('setup-permission-list');
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

    public function resetForm()
    {
        $this->reset([
            'package_name',
            'sub_package_name',
            'id',
        ]);
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
            ->when($this->search, function ($q) {
                $q->where('sub_package_name', 'like', "%$this->search%")
                    ->orWhere('package_name', 'like', "%$this->search%");
            })
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
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

    public function resetFormValidation()
    {
        $this->resetValidation();
    }

}
