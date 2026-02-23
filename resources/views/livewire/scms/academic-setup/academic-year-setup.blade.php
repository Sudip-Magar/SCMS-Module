<div x-data="{ drawer: @entangle('drawer'), deleteModal: @entangle('deleteModal') }" x-init="$store.academicYearSetup.init()">
    <x-header class="text-lg header" title="{{ __('Academic Year Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-black text-xs" placeholder="{{ __('Search...') }}"
                wire:model.live.debounce="search" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')"
                    @click.prevent="$wire.drawer = true, $wire.resetFormValidation(), $store.academicYearSetup.resetForm()"
                    responsive icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5" />
            </div>
        </x-slot:actions>
    </x-header>
    <x-card class="text-xs" x-bind:class="$store.darkmode.toggle ? 'bg-gray-900 text-white' : 'bg-white text-black'">
        <x-pagination-filter />
        <x-table x-bind:class="$store.darkmode.toggle ? 'bg-gray-900 text-white' : 'bg-white text-black'"
            class="text-xs" :headers="$headers" :rows="$years" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $year)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" spinner="edit({{ $year->id }})" class="btn-ghost  btn-xs text-indigo-500"
                        tooltip-bottom="{{ __('Edit') }}" x-cloak
                        @click.prevent="$store.academicYearSetup.edit({{ $year->id }})" />

                    <x-button icon="o-trash"
                        @click.prevent="$store.academicYearSetup.deleteDate({{ $year->id }}); $wire.deleteModal = true"
                        class="btn-ghost btn-xs text-red-500" tooltip-bottom="{{ __('Delete') }}" x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="deleteModal" title="{{ __('Are you sure?') }}" box-class="w-120">
        <p class="text-red-500 border-b pb-2">
            {{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                @click.prevent="$store.academicYearSetup.resetDeleteData(); $wire.deleteModal = false;" />

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                @click.prevent="$wire.delete($store.academicYearSetup.academic_year_id)" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs"
        x-on:shown.window="$store.academicYearSetup.init()">
        <x-card separator progress-indicator="saveAcademicYear">
            <x-form no-separator @submit.prevent="$store.academicYearSetup.saveAcademicYear()"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label for="start_year_en"
                            class="fieldset-legend mb-0.5">{{ __('Enter Start Year (NP)') }}:</label>
                        <input x-model="$store.academicYearSetup.AcademicYearData.start_year_np" id="start_year_en"
                            class="input nepali-date" data-sync="start" placeholder="{{ __('Enter Start Year (NP)') }}"
                            x-cloak>
                        <template x-if="$store.academicYearSetup.errors?.start_year_np">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.start_year_np"></small>
                        </template>
                    </div>

                    <div>
                        <label for="start_year_np"
                            class="fieldset-legend mb-0.5">{{ __('Enter Start Year (EN)') }}:</label>
                        <input class="input english-date" data-sync="start" type="date" id="start_year_np"
                            placeholder="{{ __('Enter Start Year (EN)') }}"
                            x-model="$store.academicYearSetup.AcademicYearData.start_year_en">
                        <template x-if="$store.academicYearSetup.errors?.start_year_en">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.start_year_en"></small>
                        </template>

                    </div>

                    <div>
                        <label for="end_year_en"
                            class="fieldset-legend mb-0.5">{{ __('Enter End Year (NP)') }}:</label>
                        <input class="input nepali-date" data-sync="end" placeholder="{{ __('Enter End Year (NP)') }}"
                            x-model="$store.academicYearSetup.AcademicYearData.end_year_np" id="end_year_en">
                        <template x-if="$store.academicYearSetup.errors?.end_year_np">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.end_year_np"></small>
                        </template>
                    </div>

                    <div>
                        <label for="end_year_np"
                            class="fieldset-legend mb-0.5">{{ __('Enter End Year (EN)') }}:</label>
                        <input class="input english-date" data-sync="end" type="date" id="end_year_np"
                            placeholder="{{ __('Enter End Year (EN)') }}"
                            x-model="$store.academicYearSetup.AcademicYearData.end_year_en">
                        <template x-if="$store.academicYearSetup.errors?.end_year_en">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.end_year_en"></small>
                        </template>

                    </div>

                    <div>
                        <x-select label="{{ __('Status') }}:" x-model="$store.academicYearSetup.AcademicYearData.status"
                            :options="$status" option-value="value" option-label="label" />
                        <template x-if="$store.academicYearSetup.errors?.status">
                            <small class="text-red-500" x-text="$store.academicYearSetup.errors.status"></small>
                        </template>
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}" @click.prevent="$wire.drawer = false, $store.academicYearSetup.resetForm()"
                        class="btn-xs py-3.5 px-3.5" />
                    <x-button label="{{ __('Save') }}" spinner="saveAcademicYear" type="submit"
                        class="btn-primary btn-xs py-3.5 px-3.5" />
                </x-slot:actions>

            </x-form>

        </x-card>
    </x-modal>
</div>
@script
    <script>
        Alpine.store('academicYearSetup', {
            AcademicYearData: @json($yearForm ?? []),
            academic_year_id: null,
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
                    if (response?.original?.data) {
                        this.AcademicYearData = response.original.data
                    }

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
            },

            deleteDate(id) {
                this.academic_year_id = id;

            },

            resetDeleteData() {
                this.academic_year_id = null


            },
        });
    </script>
@endscript
