<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Livewire\Forms\AcademicSetup\AcademicYearForm;
use App\Models\AcademicSetup\AcademicYear;
use App\Models\AuditModel\AuditAcademicYear;
use App\Traits\WithCustomPagination;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Enums\StatusState;
use Illuminate\Pagination\LengthAwarePaginator;

class AcademicYearSetup extends Component
{
    use Toast, WithCustomPagination;

    public string $search = '';
    public AcademicYearForm $yearForm;

    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'start_year_en', 'direction' => 'asc'];
    public $status;
    public $yearSelect;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        // $this->yearSelect = ['altFormat' => 'd/m/Y'];
        // dd($this->status);
    }

    public function saveAcademicYear($data)
    {
        try {
            if (($has_errors = validateField($data, $this->yearForm->getRules())) !== true) {
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
        } catch(\Exception){
            $this->error('Something went wrong', position:'toast-bottom');
        }

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
        try{
            auditTableEntry(AuditAcademicYear::class,$academicYear->toArray(), 'delete');
            $is_delete = $academicYear->deleteOrFail();
            if(!$is_delete){
                $this->error('Failed to delete the Academic Year', position:"toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Academic Year Delete Successfully', position:'toast-bottom');
        }catch(\Exception){
            $this->error('Something went wrong', position:'toast-bottom');
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
            ->selectRaw("id, start_year_en, start_year_np, end_year_en, end_year_np,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status")
                            ->when($this->search, fn($query)=>$query->where('start_year_en', 'like',"%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'start_year_en', 'label' => __('Start Year (EN)'), 'class' => 'w-50',],
            ['key' => 'start_year_np', 'label' => __('Start Year (NP)'), 'sortable' => false],
            ['key' => 'end_year_en', 'label' => __('End Year (EN)'), 'sortable' => false],
            ['key' => 'end_year_np', 'label' => __('End Year (NP)'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
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
}
