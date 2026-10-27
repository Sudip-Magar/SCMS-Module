<?php

namespace App\Livewire\Scms\Numbering;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\Numbering\AcademicNumberingForm;
use App\Models\Numbering\AdmissionNumbering;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicNumbering extends Component
{
    use Toast, WithCustomPagination;

    public string $search = '';
    public AcademicNumberingForm $numberingForm;
    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'academic_level', 'direction' => 'asc'];
    public $status;
    public $academic_level;

    public $allowedPermissions = [
        'list' => false,
        'create' => false,
        'edit' => false,
        'delete' => false,
    ];

    public function mount()
    {
        $this->allowedPermissions = [
            'list' => authorizeUserCheck('student_setup-admission_numbering-list'),
            'create' => authorizeUserCheck('student_setup-admission_numbering-create'),
            'edit' => authorizeUserCheck('student_setup-admission_numbering-edit'),
            'delete' => authorizeUserCheck('student_setup-admission_numbering-delete'),
        ];
        authorizeUserModal('student_setup-admission_numbering-list');

        $this->status = backedEnumAsArray(StatusState::cases());
        $this->academic_level = backedEnumAsArray(AcademicLevelState::cases());
    }

    public function saveAdmissionNumbering($data)
    {
        try {
            if (($has_error = validateField($data, $this->numberingForm->getRules()))) {
                return $has_error;
            }
            $is_saved = $this->numberingForm->performSave($data);
            if (!$is_saved) {
                $this->error('Admission Numbering Could not be Saved', position: 'toast-bottom');
                return false;
            }

            $this->success('Admission Numbering ' . ($this->numberingForm->id ? 'updated' : 'Saved') . ' Successfully', position: 'toast-bottom');
            $this->drawer = false;
            $this->resetForm();
            $this->resetFormValidation();
        } catch (\Exception $e) {
            $this->error('Something went wrong', position: 'toast-bottom');
        }
    }

    public function resetForm()
    {
        $this->title = 'Create Admission Numbering';
        $this->numberingForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit(AdmissionNumbering $numbering)
    {
        $this->title = 'Edit Admission Numbering';
        $this->numberingForm->id = $numbering->id;

        $this->drawer = true;
        $this->js('$store.numberingSetup.init(' . $numbering . ')');
    }

    public function delete(AdmissionNumbering $numbering)
    {
        try {
            authorizeUserModal('student_setup-admission_numbering-delete');
            AuditTableEntryEvent::dispatch('admission_numberings', $numbering, 'delete');
            $is_deleted = $numbering->deleteOrFail();

            if (!$is_deleted) {
                $this->error('Failed to delete the Admission Numbering', position: "toast-bottom");
                return false;
            }

            $this->deleteModal = false;
            $this->error('Admission Numbering Delete Successfully', position: 'toast-bottom');
        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.scms.numbering.academic-numbering', [
            'numbering_data_list' => $this->numberingData(),
            'headers' => $this->headers(),
        ]);
    }

    public function numberingData(): LengthAwarePaginator
    {
        return AdmissionNumbering::query()
            ->selectRaw('id, prefix, suffix, start, current, body_length, total_length,  CONCAT(
                            UCASE(SUBSTRING(`academic_level`, 1, 1)),
                            LOWER(SUBSTRING(`academic_level`, 2))) as academic_level,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status')
            ->when($this->search, fn($query) => $query->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'academic_level', 'label' => __('Academic Level'), 'class' => 'w-50',],
            ['key' => 'prefix', 'label' => __('Prefix'), 'sortable' => false],
            ['key' => 'suffix', 'label' => __('Suffix'), 'sortable' => false],
            ['key' => 'start', 'label' => __('Start'), 'sortable' => false],
            ['key' => 'current', 'label' => __('Current'), 'sortable' => false],
            ['key' => 'body_length', 'label' => __('Body Length'), 'sortable' => false],
            ['key' => 'total_length', 'label' => __('Total Length'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }
}
