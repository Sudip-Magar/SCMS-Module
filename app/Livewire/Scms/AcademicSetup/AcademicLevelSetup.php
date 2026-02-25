<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicLevelForm;
use App\Models\AcademicSetup\AcademicLevel;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicLevelSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicLevelForm $levelForm;

    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $status;
    public $type;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->type = collect(AcademicLevelState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ]);
    }

    public function saveAcademicLevel()
    {
        $is_saved = $this->levelForm->performLevelSave();

        if (!$is_saved) {
            $this->error('Academic Level Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Academic Level " . ($this->levelForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();

    }

    public function edit(AcademicLevel $level)
    {
        $this->title = "Edit Level";
        $this->levelForm->id = $level->id;
        $this->levelForm->name = $level->name;
        $this->levelForm->short_name = $level->short_name;
        $this->levelForm->status = $level->status;
        $this->levelForm->type = $level->type;
        $this->drawer = true;
    }

    public function delete(AcademicLevel $level)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_levels', $level, 'delete');
            $is_delete = $level->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Academic Level', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Level Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-level-setup', [
            'level_data_list' => $this->levelData(),
            'headers' => $this->headers(),
        ]);
    }

    public function levelData(): LengthAwarePaginator
    {
        return AcademicLevel::query()
            ->selectRaw("id, name, short_name,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status,
                            CONCAT(
                            UCASE(SUBSTRING(`type`, 1, 1)),
                            LOWER(SUBSTRING(`type`, 2))) as type")
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
            ['key' => 'type', 'label' => __('Academic Type'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Level';
        $this->levelForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
