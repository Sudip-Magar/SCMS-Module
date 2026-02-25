<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicRoomForm;
use App\Models\AcademicSetup\AcademicRoom;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicRoomSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicRoomForm $roomForm;
    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $status;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();
    }

    public function saveAcademicRoom()
    {
        $is_saved = $this->roomForm->performRoomSave();

        if (!$is_saved) {
            $this->error('Room Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Room " . ($this->roomForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();

    }

    public function edit(AcademicRoom $room)
    {
        $this->title = "Edit Room";
        $this->roomForm->id = $room->id;
        $this->roomForm->name = $room->name;
        $this->roomForm->short_name = $room->short_name;
        $this->roomForm->floor_no = $room->floor_no;
        $this->roomForm->block_no = $room->block_no;
        $this->roomForm->status = $room->status;
        $this->drawer = true;
    }

    public function delete(AcademicRoom $room)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_rooms', $room, 'delete');
            $is_delete = $room->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Room', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Room Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-room-setup', [
            'room_data_list' => $this->roomData(),
            'headers' => $this->headers(),
        ]);
    }

    public function roomData(): LengthAwarePaginator
    {
        return AcademicRoom::query()
            ->selectRaw("id, name, short_name, floor_no, block_no, CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status")
            ->when($this->search, fn($query) => $query->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => __('Name'), 'class' => 'w-100',],
            ['key' => 'short_name', 'label' => __('Short Name'), 'sortable' => false],
            ['key' => 'floor_no', 'label' => __('Floor No.'), 'sortable' => false],
            ['key' => 'block_no', 'label' => __('Block No.'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Room';
        $this->roomForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
