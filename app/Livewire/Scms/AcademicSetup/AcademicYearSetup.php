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
    public array $sortBy = ['column' => 'start_year', 'direction' => 'asc'];
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
            ->selectRaw('id, start_year, end_year, status')
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => 'Action', 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'start_year', 'label' => 'Start Year', 'class' => 'w-50',],
            ['key' => 'end_year', 'label' => 'End Year', 'sortable' => false],
            ['key' => 'status', 'label' => 'Status', 'sortable' => false],
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
