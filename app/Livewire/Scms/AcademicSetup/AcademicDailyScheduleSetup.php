<?php

namespace App\Livewire\Scms\AcademicSetup;

use App\Enums\AcademicLevelState;
use App\Enums\ShiftStatusState;
use App\Enums\StatusState;
use App\Events\AuditTableEntryEvent;
use App\Livewire\Forms\AcademicSetup\AcademicDailySchedulForm;
use App\Models\AcademicSetup\AcademicDailySchedule;
use App\Traits\WithCustomPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Mary\Traits\Toast;

class AcademicDailyScheduleSetup extends Component
{
    use Toast, WithCustomPagination;
    public string $search = '';
    public AcademicDailySchedulForm $scheduleForm;
    public bool $drawer = false;
    public bool $deleteModal = false;
    public $title;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
    public $status;
    public $academic_level;
    public $shift;

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

        $this->shift = collect(ShiftStatusState::cases())
            ->map(fn($item) => [
                'value' => $item->name,
                'label' => $item->value
            ])
            ->toArray();
    }

    public function saveAcademicSchedule()
    {
        $is_saved = $this->scheduleForm->performScheduleSave();

        if (!$is_saved) {
            $this->error('Daily Schedule Could not be Saved', position: 'toast-bottom');
            return false;
        }

        $this->success("Daily Schedule " . ($this->scheduleForm->id ? 'Updated' : 'Saved') . " Successfully", position: 'toast-bottom');
        $this->drawer = false;
        $this->resetForm();
        $this->resetFormValidation();
    }

    public function edit(AcademicDailySchedule $schedule)
    {
        $this->title = "Edit Daily Schedule";
        $this->scheduleForm->id = $schedule->id;
        $this->scheduleForm->academic_level = $schedule->academic_level;
        $this->scheduleForm->day = $schedule->day;
        $this->scheduleForm->total_period = $schedule->total_period;
        $this->scheduleForm->shift = $schedule->shift;
        $this->scheduleForm->status = $schedule->status;
        $this->drawer = true;
    }

    public function delete(AcademicDailySchedule $schedule)
    {
        try {
            AuditTableEntryEvent::dispatch('academic_daily_schedules', $schedule, 'delete');
            $is_delete = $schedule->deleteOrFail();
            if (!$is_delete) {
                $this->error('Failed to delete the Daily Schedule', position: "toast-bottom");
                return false;
            }
            $this->deleteModal = false;
            $this->error('Daily Schedule Delete Successfully', position: 'toast-bottom');

        } catch (\Exception $exception) {
            $this->error('Something went wrong ' . $exception->getMessage(), position: 'toast-bottom');

        }
    }

    public function render()
    {
        return view('livewire.scms.academic-setup.academic-daily-schedule-setup', [
            'schedule_data_list' => $this->scheduleData(),
            'headers' => $this->headers(),
        ]);
    }

    public function scheduleData(): LengthAwarePaginator
    {
        return AcademicDailySchedule::query()
            ->selectRaw("id, name, day, total_period, shift, CONCAT(
                            UCASE(SUBSTRING(`status`, 1, 1)),
                            LOWER(SUBSTRING(`status`, 2))) as status, CONCAT(
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
            ['key' => 'name', 'label' => __('Name'), 'class' => 'w-100',],
            ['key' => 'academic_level', 'label' => __('Academic Level'),],
            ['key' => 'day', 'label' => __('Day'), 'sortable' => false],
            ['key' => 'total_period', 'label' => __('Total Period'), 'sortable' => false],
            ['key' => 'shift', 'label' => __('Shift'), 'sortable' => false],
            ['key' => 'status', 'label' => __('Status'), 'sortable' => false],
        ];
    }

    public function resetForm()
    {
        $this->title = 'Create Daily Schedule';
        $this->scheduleForm->reset();
    }

    public function resetFormValidation()
    {
        $this->resetForm();
        $this->resetValidation();
    }
}
