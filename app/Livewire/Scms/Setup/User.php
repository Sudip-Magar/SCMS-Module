<?php

namespace App\Livewire\Scms\Setup;

use App\Enums\StatusState;
use App\Enums\UserTypeStatusState;
use App\Events\AuditTableEntryEvent;
use App\Http\Controllers\searchSelect2Controller;
use App\Livewire\Forms\Setup\UserForm;
use App\Models\User as UserModel;
use App\Traits\WithCustomPagination;
use Livewire\Component;
use Mary\Traits\Toast;

class User extends Component
{
    use Toast, WithCustomPagination;

    public $search = '';
    public bool $drawer = false;
    public bool $deleteModal = false;
    public UserForm $userForm;
    public $title = 'Create User';
    public array $sortBy = ['column' => 'username', 'direction' => 'asc'];
    public $status;
    public $profile_types;
    public $profiles;
    public $roles;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->profile_types = collect(UserTypeStatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();
    }

    public function saveUser($data)
    {
        try {
            if ($has_error = validateField($data, $this->userForm->getRules())) {
                return $has_error;
            }

            $is_saved = $this->userForm->performSaveUser($data);
            if (!$is_saved) {
                $this->error('User Could not be saved', position: 'toast-bottom');
                return false;
            }

            $this->success('User ' . ($this->userForm->id ? 'Updated' : 'Created') . ' Successfully', position: 'toast-bottom');
            $this->drawer = false;
            $this->resetForm();
            $this->resetFormValidation();
        } catch (\Exception $exception) {
            $this->error("Something went wrong", position: 'toast-bottom');
        }
    }

    public function resetForm()
    {
        $this->userForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetValidation();
    }

    public function edit(UserModel $user)
    {
        $this->title = 'Edit User';
        $this->userForm->id = $user->id;
//        $this->userForm->username = $user->username;
//        $this->userForm->profile_type = $user->profile_type;
//        $this->userForm->profile_id = $user->profile_id;
//        $this->userForm->short_name = $user->short_name;
//        $this->userForm->status = $user->status;
//        $this->userForm->role_id = $user->roles->pluck('id')->first();
        $this->drawer = true;

        $userData = [
            'id' => $user->id,
            'username' => explode('@', $user->username)[0],
            'profile_type' => $user->profile_type,
            'profile_id' => $user->profile_id,
            'short_name' => $user->short_name,
            'status' => $user->status,
            'role_id' => $user->roles->pluck('id')->first(), // single role ID
        ];

        $selectSearch = app(searchSelect2Controller::class);
        $this->profiles = $selectSearch->getProfile(request());
        $this->roles = $selectSearch->getRole(request());
        $this->js('$store.userSetup.profiles = ' . json_encode($this->profiles));
        $this->js('$store.userSetup.roles = ' . json_encode($this->roles));
        $this->js('$store.userSetup.init(' . json_encode($userData) . ')');

    }

    public function delete(UserModel $user)
    {
        try {
            AuditTableEntryEvent::dispatch('users', $user, 'delete');
            $is_delete = $user->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the User', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('User Delete Successfully', position: 'toast-bottom');
        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');
        }
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
            // Join students if profile_type is Student
            ->leftJoin('students', function ($join) {
                $join->on('students.id', '=', 'users.profile_id')
                    ->where('users.profile_type', \App\Models\Student\Student::class);
            })
            // Join pivot table for roles
            ->leftJoin('model_has_roles', function ($join) {
                $join->on('model_has_roles.model_id', '=', 'users.id')
                    ->where('model_has_roles.model_type', UserModel::class);
            })
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            // Select required fields
            ->selectRaw("
            users.id as userId,
            users.username,
            users.status,
            CONCAT(students.first_name, ' ', students.last_name) as full_name,
            GROUP_CONCAT(roles.name SEPARATOR ', ') as role_name,
            CONCAT(UCASE(SUBSTRING(users.status, 1, 1)), LOWER(SUBSTRING(users.status, 2))) as status_formatted
        ")
            ->when($this->search, fn($query) => $query->where('users.username', 'like', "%$this->search%"))
            ->groupBy('users.id', 'users.username', 'users.status', 'students.first_name', 'students.last_name')
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __("Action"), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'username', 'label' => __('Username'), 'class' => 'w-60'],
            ['key' => 'full_name', 'label' => __('Full Name'), 'sortable' => false],
            ['key' => 'role_name', 'label' => __('Role'), 'sortable' => false],
            ['key' => 'status_formatted', 'label' => __('Status'), 'sortable' => false],
        ];
    }
}
