<?php

namespace App\Livewire\Forms\Setup;

use App\Enums\StatusState;
use App\Enums\UserTypeStatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class UserForm extends Form
{
    public $id;
    public $username = '';
    public $profile_type = UserTypeStatusState::TEACHER->name;
    public $profile_id = '';
    public $role_id = '';
    public $password = '';
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $this->id
            ],
            'profile_type' => ['required', 'string', 'max:255'],
            'profile_id' => ['nullable', 'integer','unique:users,profile_id,' . $this->id],
            'role_id' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8,'.$this->id],
            'status' => ['required'], // add this
        ];
    }

    public function performSaveUser($data)
    {
//        dd($data);
        $firstLetter = '';
        $lastLetter = '';
        $roleId = $data['role_id'];
        unset($data['role_id']);

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
            authorizeUserCheck('setup-user-edit');
        } else {
            $data['created_by'] = Auth::user()->id;
            authorizeUserCheck('setup-user-create');
        }

        if ($data['profile_type'] == UserTypeStatusState::STUDENT->name) {
            $student = Student::find($data['profile_id']);
            $firstLetter = strtoupper(substr($student->first_name, 0, 1));
            $lastLetter = strtoupper(substr($student->last_name, 0, 1));
            $data['username'] = $data['username']. '@milton.com';
            $data['short_name'] = $firstLetter . $lastLetter;
            $data['profile_type'] = Student::class;
        }

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        $is_saved = User::updateOrCreate(['id' => $this->id], $data);
        AuditTableEntryEvent::dispatch('users', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            $is_saved->syncRoles($roleId);
            return true;
        }

        return false;


    }
}
