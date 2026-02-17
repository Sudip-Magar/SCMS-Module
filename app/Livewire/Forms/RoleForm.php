<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    public $id;
    #[Validate('required')]
    public string $name = '';

    public function saveRoleSetup(){
        $this->validate();
    }

}
