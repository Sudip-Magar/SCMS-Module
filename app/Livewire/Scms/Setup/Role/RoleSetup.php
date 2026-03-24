<?php

namespace App\Livewire\Scms\Setup\Role;


use App\Traits\WithCustomPagination;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class RoleSetup extends Component
{
    use Toast, WithCustomPagination;

    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $search = '';

    public function delete(Role $role)
    {
        try {
            $is_deleted = $role->delete();
            if (!$is_deleted) {
                $this->error('Could not delete role.', position: 'toast-bottom');
            }
            $this->drawer = false;
            $this->error('Role Delete Successfully.', position: 'toast-bottom');
        } catch (\Exception $exception) {
            $this->error('Something Went Wrong', position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.scms.setup.role.role-setup', [
            'roles' => $this->RoleData(),
            'headers' => $this->headers(),
        ]);
    }

    public function RoleData()
    {
        return Role::query()
            ->selectRaw('id, name, created_at, updated_at')
            ->when($this->search, fn($query) => $query->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => __('Name'), 'class' => 'w-50'],
            ['key' => 'created_at', 'label' => __('Created At'), 'sortable' => false],
            ['key' => 'updated_at', 'label' => __('Updated At'), 'sortable' => false],
        ];
    }
}
