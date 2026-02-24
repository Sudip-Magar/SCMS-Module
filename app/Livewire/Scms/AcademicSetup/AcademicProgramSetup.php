<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\StatusState;
use App\Livewire\Forms\AcademicSetup\AcademicProgramForm;
use App\Models\AcademicSetup\AcademicProgram;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicProgramSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicProgramForm $programForm;
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

    public function saveAcademicProgram()
    {
        try {
            $is_saved = $this->programForm->performProgramSave();

            if (!$is_saved) {
                $this->error('Academic Program Could not be Saved', position: 'toast-bottom');
                return false;
            }

            $this->success("Academic Program " . ($this->programForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
            $this->drawer = false;
            $this->resetForm();
            $this->resetFormValidation();
        } catch (\Exception $exception) {
            $this->error('Something went wrong'. $exception->getMessage(), position: 'toast-bottom');
        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-program-setup', [
            'program_data_list' => $this->programData(),
            'headers' => $this->headers()
        ]);
    }

    public function programData(): LengthAwarePaginator
    {
        return AcademicProgram::query()
            ->selectRaw("id, name, short_name,  CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status")
            ->when($this->search, fn($query) => $query->where('start_year_en', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-16 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => __('Name'), 'class' => 'w-50',],
            ['key' => 'short_name', 'label' => __('Short Name'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Program';
        $this->programForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
