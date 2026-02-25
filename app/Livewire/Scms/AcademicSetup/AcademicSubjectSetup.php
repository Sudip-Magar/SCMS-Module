<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicSubjectForm;
use App\Models\AcademicSetup\AcademicSubject;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicSubjectSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicSubjectForm $subjectForm;
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

    public function saveAcademicSubject()
    {
        $is_saved = $this->subjectForm->performSubjectSave();

        if (!$is_saved) {
            $this->error('Academic Subject Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Academic Subject " . ($this->subjectForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();

    }

    public function edit(AcademicSubject $subject)
    {
        $this->title = "Edit Subject";
        $this->subjectForm->id = $subject->id;
        $this->subjectForm->name = $subject->name;
        $this->subjectForm->short_name = $subject->short_name;
        $this->subjectForm->status = $subject->status;
        $this->subjectForm->code = $subject->code;
        $this->subjectForm->type = $subject->type;
        $this->drawer = true;
    }

    public function delete(AcademicSubject $subject)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_subjects', $subject, 'delete');
            $is_delete = $subject->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Academic Subject', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Subject Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-subject-setup', [
            'subject_data_list' => $this->subjectData(),
            'headers'=> $this->headers(),
        ]);
    }

    public function subjectData(): LengthAwarePaginator
    {
        return AcademicSubject::query()
            ->selectRaw("id, name, short_name, code, CONCAT(
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
            ['key' => 'code', 'label' => __('Code'), 'sortable' => false],
            ['key' => 'type', 'label' => __('Academic Type'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Subject';
        $this->subjectForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
