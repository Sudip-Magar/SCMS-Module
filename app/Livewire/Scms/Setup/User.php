<?php

namespace App\Livewire\Scms\Setup;

use App\Enums\StatusState;
use App\Enums\UserTypeStatusState;
use App\Livewire\Forms\Setup\UserForm;
use Livewire\Component;
use App\Models\User as UserModel;
use Mary\Traits\Toast;

class User extends Component
{
    use Toast;
    public bool $drawer = false;
    public UserForm $userForm;
    public $title ='Create User';
    public array $sortBy = ['column' => 'username', 'direction' => 'asc'];
    public $status;
    public $user_types;

    public function mount(){
        $this->status = StatusState::cases();
        $this->user_types = UserTypeStatusState::cases();
        // dd($this->status);
    }

    public function saveUser(){
        // dd($this->userForm);
        $this->userForm->performSaveUser();
    }

    public function render()
    {
        return view('livewire.scms.setup.user', [
            'users' => $this->userData(),
            'headers' => $this->headers(),
        ]);
    }

    public function userData()
    {
        return UserModel::query()
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->selectRaw("users.id as userId, users.username, users.role_id, users.user_type, users.status, roles.name as role_name")
            ->orderBy(...array_values($this->sortBy))
            ->get();

    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'username', 'label' => 'Name', 'class' => 'w-50'],
            ['key' => 'user_type', 'label' => 'User Type', 'sortable' => false],
            ['key' => 'role_name', 'label' => 'Role', 'sortable' => false],
            ['key' => 'status', 'label' => 'Status', 'sortable' => false],
        ];
    }

    public function resetForm()
    {
      $this->userForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetValidation();
    }
}
