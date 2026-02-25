<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicSectionlState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicSectionFrom;
use App\Models\AcademicSetup\AcademicSection;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicSectionSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicSectionFrom $sectionFrom;
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

        $this->type = collect(AcademicSectionlState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ]);
    }

    public function saveAcademicSection()
    {
        $is_saved = $this->sectionFrom->performSectionSave();

        if (!$is_saved) {
            $this->error('Academic Section Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Academic Section " . ($this->sectionFrom->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();

    }

    public function edit(AcademicSection $section)
    {
        $this->title = "Edit Section";
        $this->sectionFrom->id = $section->id;
        $this->sectionFrom->name = $section->name;
        $this->sectionFrom->short_name = $section->short_name;
        $this->sectionFrom->status = $section->status;
        $this->sectionFrom->type = $section->type;
        $this->drawer = true;
    }

    public function delete(AcademicSection $section)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_sections', $section, 'delete');
            $is_delete = $section->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Academic Section', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Section Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-section-setup', [
            'section_data_list' => $this->sectionData(),
            'headers' => $this->headers()
        ]);
    }

    public function sectionData(): LengthAwarePaginator
    {
        return AcademicSection::query()
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
        $this->title = 'Create Section';
        $this->sectionFrom->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
