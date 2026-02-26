<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\AcademicSystemState;
use App\Enums\StatusState;
use App\Http\Controllers\searchSelect2Controller;
use App\Livewire\Forms\AcademicSetup\AcademicStructureForm;
use App\Models\AcademicSetup\AcademicStructure;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicStructureSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicStructureForm $structureForm;
    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $status;
    public $academic_level;
    public $academic_system;
    public $academicYears;
    public $academicPrograms;
    public $academicFaculty;

    public $academicLevel;
    public $academicRooms;
    public $academicSections;

    public function mount()
    {
        $this->status = collect(StatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->academic_level = collect(AcademicLevelState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();

        $this->academic_system = collect(AcademicSystemState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();
    }

    public function saveAcademicStructure($data)
    {
        try {
            // if (($has_errors = validateField($data, $this->structureForm->getRules()))) {
            //     return $has_errors;
            // }
            $is_saved = $this->structureForm->performAcademicStructure($data);

            if (!$is_saved) {
                $this->error(' Academic Structure Could not be Saved', position: 'toast-bottom');
                return false;
            }

            $this->success('Academic Structure ' . ($this->structureForm->id ? 'Updated' : 'Saved') . ' Successgfully', position: 'toast-bottom');
            $this->drawer = false;
            return true;

        } catch (\Exception $exception) {
            $this->error('Something went wrong', position: 'toast-bottom');
        }
    }

    public function edit(AcademicStructure $structure)
    {
        $this->title = 'Edit Academic Structure';
        $this->structureForm->id = $structure->id;
        $this->structureForm->name = $structure->name;
        $this->structureForm->academic_level = $structure->academic_level;
        $this->structureForm->year_id = $structure->year_id;
        $this->structureForm->program_id = $structure->program_id;
        $this->structureForm->faculty_id = $structure->faculty_id;
        $this->structureForm->level_id = $structure->level_id;
        $this->structureForm->room_id = $structure->room_id;
        $this->structureForm->section_id = $structure->section_id;
        $this->structureForm->academic_system = $structure->academic_system;
        $this->structureForm->status = $structure->status;
        $selectSearch = app(searchSelect2Controller::class);

        $this->drawer = true;
        $this->academicPrograms = $selectSearch->getAcademicProgram(request());
        $this->js('$store.academicStructureSetup.academicPrograms = '. json_encode($this->academicPrograms).'');
        $this->js('$store.academicStructureSetup.init('. $structure . ')');
        // return response()->json(['data' => $structure->toArray()]);
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-structure-setup', [
            'structure_data_list' => $this->structureData(),
            'headers' => $this->headers(),
        ]);
    }

    public function structureData(): LengthAwarePaginator
    {
        return AcademicStructure::query()
            ->leftJoin('academic_years', 'academic_years.id', '=', 'academic_structures.year_id')
            ->leftJoin('academic_programs', 'academic_programs.id', '=', 'academic_structures.program_id')
            ->leftJoin('academic_faculties', 'academic_faculties.id', '=', 'academic_structures.faculty_id')
            ->leftJoin('academic_levels', 'academic_levels.id', '=', 'academic_structures.level_id')
            ->leftJoin('academic_rooms', 'academic_rooms.id', '=', 'academic_structures.room_id')
            ->leftJoin('academic_sections', 'academic_sections.id', '=', 'academic_structures.section_id')
            ->selectRaw("
            academic_structures.id AS id,
            academic_structures.name AS name,

            CONCAT(UCASE(SUBSTRING(academic_structures.status, 1, 1)),
            LOWER(SUBSTRING(academic_structures.status, 2))) as status,

            CONCAT(UCASE(SUBSTRING(academic_structures.academic_system, 1, 1)),
            LOWER(SUBSTRING(academic_structures.academic_system, 2))) as academic_system,

            CONCAT(UCASE(SUBSTRING(academic_structures.academic_level, 1, 1)),
            LOWER(SUBSTRING(academic_structures.academic_level, 2))) as academic_level,

            academic_years.start_year_en AS year,
            academic_programs.name AS program,
            academic_faculties.name AS faculty,
            academic_levels.name AS level,
            academic_rooms.name AS room,
            academic_sections.name AS section
            ")
            ->when($this->search, fn($query) => $query->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage, pageName: 'page');
    }

    public function headers()
    {
        return [
            ['key' => 'action', 'label' => __('Action'), 'class' => 'w-14 text-center', 'sortable' => false],
            ['key' => 'name', 'label' => __('Name'),],
            ['key' => 'academic_level', 'label' => __('Academic Level'), 'sortable' => false],
            ['key' => 'year', 'label' => __('Year'), 'sortable' => false],
            ['key' => 'program', 'label' => __('Program'), 'sortable' => false],
            ['key' => 'faculty', 'label' => __('Faculty'), 'sortable' => false],
            ['key' => 'level', 'label' => __('Level'), 'sortable' => false],
            ['key' => 'room', 'label' => __('Room'), 'sortable' => false],
            ['key' => 'section', 'label' => __('Section'), 'sortable' => false],
            ['key' => 'academic_system', 'label' => __('Academic System'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Academic Structure';
        $this->structureForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
