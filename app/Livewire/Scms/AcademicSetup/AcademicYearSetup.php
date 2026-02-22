<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Livewire\Forms\AcademicSetup\AcademicYearForm;
use App\Models\AcademicSetup\AcademicYear;
use Livewire\Component;
use Mary\Traits\Toast;
use App\Enums\StatusState;

class AcademicYearSetup extends Component
{
    use Toast;
    public AcademicYearForm $yearForm;

    public bool $drawer = false;
    public $title = 'Create Academic Year';
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

        $this->yearSelect = ['altFormat' => 'd/m/Y'];
        // dd($this->status);
    }

    public function saveAcademicYear($data)
    {
        if (($has_errors = validateDate($data, $this->yearForm->getRules())) !== true) {
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

    }

    public function edit(AcademicYear $academicYear)
    {
        // dd($academicYear);
        $this->title = "Edit Academic Year";
        $this->yearForm->id = $academicYear->id;
        $this->yearForm->start_year_en = $academicYear->start_year_en;
        $this->yearForm->start_year_np = $academicYear->start_year_np;
        $this->yearForm->end_year_en = $academicYear->end_year_en;
        $this->yearForm->end_year_np = $academicYear->end_year_np;
        $this->yearForm->status = $academicYear->status;
        $this->drawer = true;
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-year-setup', [
            'years' => $this->yearData(),
            'headers' => $this->headers()
        ]);
    }

    public function yearData()
    {
        return AcademicYear::query()
            ->selectRaw("id, start_year_en, start_year_np, end_year_en, end_year_np,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status")
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'start_year_en', 'label' => __('Start Year (EN)'), 'class' => 'w-50',],
            ['key' => 'start_year_np', 'label' => __('Start Year (EN)'), 'sortable' => false],
            ['key' => 'end_year_en', 'label' => __('End Year (EN)'), 'sortable' => false],
            ['key' => 'end_year_np', 'label' => __('End Year (NP)'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->yearForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetValidation();
    }
}
