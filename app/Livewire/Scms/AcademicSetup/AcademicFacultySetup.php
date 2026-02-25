<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicFacultyForm;
use App\Models\AcademicSetup\AcademicFaculty;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicFacultySetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicFacultyForm $facultyForm;
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

    public function saveAcademicFaculty()
    {
        $is_saved = $this->facultyForm->performFacultySave();

        if (!$is_saved) {
            $this->error('Academic Faculty Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Academic Faculty " . ($this->facultyForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();

    }

    public function edit(AcademicFaculty $faculty)
    {
        $this->title = "Edit Faculty";
        $this->facultyForm->id = $faculty->id;
        $this->facultyForm->name = $faculty->name;
        $this->facultyForm->short_name = $faculty->short_name;
        $this->facultyForm->status = $faculty->status;
        $this->drawer = true;
    }

    public function delete(AcademicFaculty $faculty)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_faculties', $faculty, 'delete');
            $is_delete = $faculty->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Academic Faculty', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Faculty Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-faculty-setup',[
            'faculty_data_list' => $this->facultyData(),
            'headers' => $this->headers(),
        ]);
    }

    public function facultyData(): LengthAwarePaginator
    {
        return AcademicFaculty::query()
            ->selectRaw("id, name, short_name,  CONCAT(
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
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Faculty';
        $this->facultyForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
