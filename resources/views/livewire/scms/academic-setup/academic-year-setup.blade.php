<div x-data="{ drawer: @entangle('drawer') }" x-init="$store.academicYearSetup.init()">
    <x-header class="text-lg" title="Role Setup">
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" @click.prevent="$wire.drawer = true, $wire.resetFormValidation();" responsive
                    icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5" />
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-table class="text-xs" :headers="$headers" :rows="$years" :sort-by="$sortBy">
            @scope('cell_action', $year)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" spinner="edit({{ $year->id }})" class="btn-ghost  btn-xs text-indigo-500"
                        tooltip-bottom="Edit" x-cloak @click.prevent="$store.academicYearSetup.edit({{ $year->id }})" />

                    <x-button icon="o-trash" spinner="delete" class="btn-ghost  btn-xs text-red-500" tooltip-bottom="Delete"
                        x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs"
        x-on:shown.window="$store.academicYearSetup.init()">
        <x-card separator progress-indicator="saveAcademicYear">
            <x-form no-separator @submit.prevent="$store.academicYearSetup.saveAcademicYear()">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label for="" class="fieldset-legend mb-0.5">Enter Start Year (NP):</label>
                        <input x-model="$store.academicYearSetup.AcademicYearData.start_year_np"
                            class="input nepali-date" data-sync="start" placeholder="Nepali Date" x-cloak>
                        <template x-if="$store.academicYearSetup.errors?.start_year_np">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.start_year_np"></small>
                        </template>
                    </div>
                    <div>
                        <label for="" class="fieldset-legend mb-0.5">Enter Start Year (EN):</label>
                        <input class="input english-date" data-sync="start" type="date"
                            x-model="$store.academicYearSetup.AcademicYearData.start_year_en">
                        <template x-if="$store.academicYearSetup.errors?.start_year_en">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.start_year_en"></small>
                        </template>

                    </div>

                    <div>
                        <label for="" class="fieldset-legend mb-0.5">Enter End Year (NP):</label>
                        <input class="input nepali-date" data-sync="end" placeholder="Nepali Date"
                            x-model="$store.academicYearSetup.AcademicYearData.end_year_np">
                        <template x-if="$store.academicYearSetup.errors?.end_year_np">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.end_year_np"></small>
                        </template>
                    </div>
                    <div>
                        <label for="" class="fieldset-legend mb-0.5">Enter End Year (EN):</label>
                        <input class="input english-date" data-sync="end" type="date"
                            x-model="$store.academicYearSetup.AcademicYearData.end_year_en">
                        <template x-if="$store.academicYearSetup.errors?.end_year_en">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.end_year_en"></small>
                        </template>

                    </div>
                    <div>
                        <x-select label="Status:" x-model="$store.academicYearSetup.AcademicYearData.status"
                            :options="$status" option-value="value" option-label="label" />
                        <template x-if="$store.academicYearSetup.errors?.status">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.status"></small>
                        </template>
                    </div>
                    <x-slot:actions>
                        <x-button label="Cancel"
                            @click.prevent="$wire.drawer = false, $store.academicYearSetup.resetForm()" />
                        <x-button label="Save" spinner="saveAcademicYear" type="submit" class="btn-primary" />
                    </x-slot:actions>
                </div>

            </x-form>

        </x-card>
    </x-modal>
</div>
@script
    <script>
        Alpine.store('academicYearSetup', {
            AcademicYearData: @json($yearForm ?? []),
            errors: {},

            init() {
                this.setupDates();
            },

            setupDates() {
                document.querySelectorAll('.nepali-date').forEach(nepali => {
                    const key = nepali.dataset.sync;
                    if (!key) return;
                    const english = document.querySelector(
                        `.english-date[data-sync="${key}"]`
                    );
                    DateSync.attach(nepali, english);
                });
            },

            saveAcademicYear() {
                $wire.saveAcademicYear(this.AcademicYearData)
                    .then((response) => {
                        console.log(response);

                        if (response?.original?.errors) {
                            // Set errors for Alpine
                            for (let field in response.original.errors) {
                                this.errors[field] = response.original.errors[field][0];
                            }
                            return false
                        }

                        this.resetForm();

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            edit(id) {
                $wire.edit(id).then((response) => {
                    console.log(response);
                    
                }).catch((error) => {
                    console.log(error);
                })
            },

            syncWithLivewire() {
                // Update Alpine when Livewire changes yearForm
                this.AcademicYearData = @json($yearForm ?? []);

            },

            resetForm() {
                this.AcademicYearData = @json($yearForm ?? []);
                this.errors = {};
            }

        });
    </script>
@endscript
