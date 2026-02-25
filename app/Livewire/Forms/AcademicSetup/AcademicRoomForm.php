<?php

namespace App\Livewire\Forms\AcademicSetup;

use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Models\AcademicSetup\AcademicRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AcademicRoomForm extends Form
{
    public $id;
    public $name;
    public $short_name;
    public $floor_no;
    public $block_no;
    public $status = StatusState::ACTIVE->name;

    public function rules()
    {
        return [
            'name' => ['required', 'max:70',],
            'short_name' => 'nullable|max:10',
            'floor_no' => 'required|integer',
            'block_no' => 'required|integer',
            'status' => 'required',
        ];
    }

    public function performRoomSave()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_name' => $this->short_name,
            'floor_no' => $this->floor_no,
            'block_no' => $this->block_no,
            'status' => $this->status,
        ];

        if ($this->id) {
            $data['updated_by'] = Auth::user()->id;
        } else {
            $data['created_by'] = Auth::user()->id;
        }

        $is_saved = AcademicRoom::updateOrCreate(['id' => $this->id], $data);

        AuditTableEntryEvent::dispatch('academic_rooms', $is_saved, $this->id ? 'edit' : 'create');

        if ($is_saved) {
            return true;
        }

        return true;
    }
}
