<?php

namespace App\Livewire\Forms\Setup;

use App\Enums\StatusState;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public $id;
    public $username = '';
    public $user_type = '';
    public $user_id = '';
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
            'user_type' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer'],
            'role_id' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required'], // add this
        ];
    }

    public function performSaveUser()
    {
        // dd($this->username);
        $this->validate();
    }
}
