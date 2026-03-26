<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicYearForm;
use App\Models\AcademicSetup\AcademicYear;
use App\Models\AuditModel\AuditAcademicYear;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicYearSetup extends Component
{
    use Toast, WithCustomPagination;

    public string $search = '';
    public AcademicYearForm $yearForm;

    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
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
            'list' => authorizeUserCheck('academic_setup-year-list'),
            'create' => authorizeUserCheck('academic_setup-year-create'),
            'edit' => authorizeUserCheck('academic_setup-year-edit'),
            'delete' => authorizeUserCheck('academic_setup-year-delete'),
        ];

        authorizeUserModal('academic_setup-year-list');

        $this->status = backedEnumAsArray(StatusState::cases());
        $this->academic_level = backedEnumAsArray(AcademicLevelState::cases());
    }

    public function saveAcademicYear($data)
    {
        try {
            if (($has_errors = validateField($data, $this->yearForm->getRules()))) {
                return $has_errors;
            }

            $is_saved = $this->yearForm->performSaveAcademicYear($data);

            if (!$is_saved) {
                $this->error('Academic Year Could not be Saved', position: 'toast-bottom');
                return false;
            }

            $this->success("Academic Year " . ($this->yearForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
            $this->drawer = false;
            $this->resetForm();
            $this->resetFormValidation();
        } catch (\Exception) {
            $this->error('Something went wrong', position: 'toast-bottom');
        }

    }

    public function resetForm()
    {
        $this->title = 'Create Academic Year';
        $this->yearForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit(AcademicYear $academicYear)
    {
        $this->yearForm->id = $academicYear->id;
        $this->title = "Edit Academic Year";
        $this->drawer = true;
        return response()->json(['data' => $academicYear->toArray()]);
    }

    public function delete(AcademicYear $academicYear)
    {
        try {
            authorizeUserModal('academic_setup-year-delete');
            AuditTableEntryEvent::dispatch('academic_years', $academicYear, 'delete');
            $is_delete = $academicYear->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Academic Year', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Year Delete Successfully', position: 'toast-bottom');
        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-year-setup', [
            'years' => $this->yearData(),
            'headers' => $this->headers()
        ]);
    }

    public function yearData(): LengthAwarePaginator
    {
        return AcademicYear::query()
            ->selectRaw("id, name, start_year_en, start_year_np, end_year_en, end_year_np,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status,
                            CONCAT(
                            UCASE(SUBSTRING(`academic_level`, 1, 1)),
                            LOWER(SUBSTRING(`academic_level`, 2))) as academic_level")
            ->when($this->search, fn($query) => $query->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => __('Name'), 'class' => 'w-50',],
            ['key' => 'academic_level', 'label' => __('Academic_level'), 'sortable' => false],
            ['key' => 'start_year_en', 'label' => __('Start Year (EN)'), 'sortable' => false],
            ['key' => 'start_year_np', 'label' => __('Start Year (NP)'), 'sortable' => false],
            ['key' => 'end_year_en', 'label' => __('End Year (EN)'), 'sortable' => false],
            ['key' => 'end_year_np', 'label' => __('End Year (NP)'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }
}
