<div x-data="{ drawer: @entangle('drawer'), deleteModal: @entangle('deleteModal') }">
    <x-header class="text-lg header" title="{{ __('Room Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                clearable />
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" @click.prevent="$wire.drawer = true, $wire.resetFormValidation()" responsive
                    icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5" />
            </div>
        </x-slot:actions>
    </x-header>
    <x-card class="text-xs">
        <x-pagination-filter />
        <x-table class="text-xs" :headers="$headers" :rows="$structure_data_list" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $structure_data)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" spinner="edit({{ $structure_data->id }})"
                        class="btn-ghost  btn-xs text-indigo-500" tooltip-bottom="{{ __('Edit') }}" x-cloak
                        wire:click='edit({{ $structure_data->id }})' />

                    <x-button icon="o-trash"
                        @click.prevent="$store.academicStructureSetup.deleteData({{ $structure_data->id }}); $wire.deleteModal = true"
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
                @click.prevent="$store.academicStructureSetup.resetDeleteData(); $wire.deleteModal = false;" />

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                @click.prevent="$wire.delete($store.academicStructureSetup.room_id)" />
        </x-slot:actions>
    </x-modal>

    <x-modal x-effect="if (!$wire.drawer) {
            $store.academicStructureSetup.resetForm()
        }"
        wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs">
        <x-card separator progress-indicator="saveAcademicStructure">
            <x-form no-separator @submit.prevent="$store.academicStructureSetup.saveAcademicStructure"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input label="{{ __('Enter Structure Name') }}:"
                            placeholder="{{ __('Enter Understandable Structure name') }}"
                            x-model="$store.academicStructureSetup.structureData.name" />
                        <span x-text="$store.academicStructureSetup.errors?.name || ''"
                            class="text-red-500 text-xs"></span>
                    </div>
                    <div>
                        <x-select label="{{ __('Academic Level') }} " :options="$academic_level" option-value="value"
                            option-label="label" x-model="$store.academicStructureSetup.structureData.academic_level" />
                        <span x-text="$store.academicStructureSetup.errors?.academic_level || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="year_id">{{ __('Academic Year') }}: <span
                                class="text-red-500 text-sm">*</span></label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-year-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('year_id',$event.target.value)"
                                x-bind:id="'year_id'" x-bind:data-row-index="'year_id'"
                                x-model="$store.academicStructureSetup.structureData.year_id">
                                <option value="">{{ 'Add Year' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicYears.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicYears?.year_id || null">
                                    </option>
                                </template>
                            </select>
                        </div>
                        <span x-text="$store.academicStructureSetup.errors?.year_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="program_id">{{ __('Academic Program') }}: </label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-program-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('program_id',$event.target.value)"
                                x-bind:id="'program_id'" x-bind:data-row-index="'program_id'"
                                x-model="$store.academicStructureSetup.structureData.program_id">
                                <option value="">{{ 'Add Program' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicPrograms.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicPrograms?.program_id ||
                                            null">
                                    </option>
                                </template>
                            </select>

                            <span x-text="$store.academicStructureSetup.errors?.program_id || ''"
                                class="text-red-500 text-xs"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="faculty_id">{{ __('Academic Faculty') }}: </label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-faculty-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('faculty_id',$event.target.value)"
                                x-bind:id="'faculty_id'" x-bind:data-row-index="'faculty_id'"
                                x-model="$store.academicStructureSetup.structureData.faculty_id">
                                <option value="">{{ 'Add Faculty' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicFaculty.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicPrograms?.faculty_id ||
                                            null">
                                    </option>
                                </template>
                            </select>

                            <span x-text="$store.academicStructureSetup.errors?.faculty_id || ''"
                                class="text-red-500 text-xs"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="level_id">{{ __('Academic Level') }}: <span
                                class="text-red-500 text-sm">*</span></label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-level-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('level_id',$event.target.value)"
                                x-bind:id="'level_id'" x-bind:data-row-index="'level_id'"
                                x-model="$store.academicStructureSetup.structureData.level_id">
                                <option value="">{{ 'Add Level' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicLevel.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicPrograms?.level_id ||
                                            null">
                                    </option>
                                </template>
                            </select>
                        </div>
                        <span x-text="$store.academicStructureSetup.errors?.level_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="room_id">{{ __('Academic Room') }}: <span
                                class="text-red-500 text-sm">*</span></label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-room-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('room_id',$event.target.value)"
                                x-bind:id="'room_id'" x-bind:data-row-index="'room_id'"
                                x-model="$store.academicStructureSetup.structureData.room_id">
                                <option value="">{{ 'Add Room' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicRooms.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicPrograms?.room_id ||
                                            null">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <span x-text="$store.academicStructureSetup.errors?.room_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="section_id">{{ __('Academic Section') }}: </label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="academic-section-select"
                                @change.prevent="$store.academicStructureSetup.updateSelectedData('section_id',$event.target.value)"
                                x-bind:id="'section_id'" x-bind:data-row-index="'section_id'"
                                x-model="$store.academicStructureSetup.structureData.section_id">
                                <option value="">{{ 'Add Seciton' }}</option>
                                <template
                                    x-for="listItem in $store.academicStructureSetup.academicSections.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicStructureSetup?.academicPrograms?.section_id ||
                                            null">
                                    </option>
                                </template>
                            </select>
                        </div>

                        <template x-if="$store.academicStructureSetup.errors?.section_id">
                            <small class="text-red-500"
                                x-text="$store.academicStructureSetup.errors.section_id"></small>
                        </template>
                    </div>

                    <div>
                        <x-select label="{{ __('Academic System') }}" :options="$academic_system" option-value="value"
                            option-label="label"
                            x-model="$store.academicStructureSetup.structureData.academic_system" />
                        <span x-text="$store.academicStructureSetup.errors?.academic_system || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                    <div>
                        <x-select label="{{ __('Status') }}" :options="$status" option-value="value"
                            option-label="label" x-model="$store.academicStructureSetup.structureData.status" />
                        <span x-text="$store.academicStructureSetup.errors?.status || ''"
                            class="text-red-500 text-xs"></span>
                    </div>

                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}"
                        @click.prevent="$wire.drawer = false, $store.academicStructureSetup.resetForm()"
                        class="btn-xs py-3.5 px-3.5" />
                    <x-button label="{{ __('Save') }}" spinner="saveAcademicStructure" type="submit"
                        class="btn-primary btn-xs py-3.5 px-3.5" />
                </x-slot:actions>

            </x-form>

        </x-card>
    </x-modal>
</div>
@script
    <script>
        Alpine.store('academicStructureSetup', {
            structureData: @json($structureForm ?? []),
            room_id: null,
            academicYears: @json($academicYears ?? []),
            academicPrograms: @json($academicPrograms ?? []),
            academicFaculty: @json($academicFaculty ?? []),
            academicLevel: @json($academicLevel ?? []),
            academicRooms: @json($academicRooms ?? []),
            academicSections: @json($academicSections ?? []),
            select2Instances: [],
            errors: {},
            deleteData(id) {
                this.room_id = id;
            },

            resetDeleteData() {
                this.room_id = null
            },

            init(structureData) {
                this.initializeSelect2();
                console.log(this.academicPrograms);
                
                if(structureData) {
                    this.structureData = structureData;
                }
            },

            saveAcademicStructure() {
                $wire.saveAcademicStructure($store.academicStructureSetup.structureData)
                    .then((response) => {

                        this.errors = {}; // reset old errors

                        if (response?.original?.errors) {
                            // Set errors for Alpine
                            for (let field in response.original.errors) {
                                $store.academicStructureSetup.errors[field] = response.original.errors[field][
                                    0
                                ];
                            }
                            return false
                        }
                        // success
                        $store.academicStructureSetup.resetForm();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },


            initializeSelect2() {
                Alpine.nextTick(() => {
                    this.select2Configs = [{
                            selector: '.academic-year-select',
                            key: 'year_id',
                            placeholder: 'Select Academic Year',
                            module_type: 'get_academic_year',
                        },
                        {
                            selector: '.academic-program-select',
                            key: 'program_id',
                            placeholder: 'Select Academic Program',
                            module_type: 'get_academic_program',
                        },
                        {
                            selector: '.academic-faculty-select',
                            key: 'faculty_id',
                            placeholder: 'Select Academic Program',
                            module_type: 'get_academic_faculty',
                        },
                        {
                            selector: '.academic-level-select',
                            key: 'level_id',
                            placeholder: 'Select Academic Program',
                            module_type: 'get_academic_level',
                        },
                        {
                            selector: '.academic-room-select',
                            key: 'room_id',
                            placeholder: 'Select Academic Program',
                            module_type: 'get_academic_room',
                        },
                        {
                            selector: '.academic-section-select',
                            key: 'section_id',
                            placeholder: 'Select Academic Program',
                            module_type: 'get_academic_section',
                        }
                    ];

                    this.select2Configs.forEach(config => {
                        this.initSelect2Element(config);
                    });
                });
            },

            initSelect2Element(config) {
                const element = $(config.selector);
                if (element.data('select2')) {
                    element.select2('destroy');
                }

                const url = `{{ route('searchSelect2', ['module' => 'MODULE']) }}`.replace('MODULE', config
                    .module_type);

                element.select2({
                    placeholder: config.placeholder,
                    allowClear: true,
                    dropdownParent: $(config.selector).closest('.modal') || $(document.body),
                    ajax: config.module_type ? {
                        url: url,
                        delay: 250,
                        data: (params) => ({
                            term: params.term
                        }),
                        processResults: (data) => {
                            this[config.module_type] = data.results;
                            return {
                                results: data.results
                            };
                        }
                    } : null
                }).on('select2:select', (event) => {
                    const value = event.params.data.id;
                    this.updateSelectedData(config.key, value);
                    $wire.set(`structureForm.${config.key}`, value);
                }).on('select2:clear', () => {
                    this.updateSelectedData(config.key, null);
                    $wire.set(`structureForm.${config.key}`, null);
                });

                // Store instance
                this.select2Instances.push({
                    element: config.selector,
                    key: config.key
                });

                // Set initial value
                if (this.structureData[config.key]) {
                    const option = new Option(
                        this.structureData[config.key],
                        this.structureData[config.key],
                        true, true
                    );
                    element.append(option).trigger('change');
                }
            },

            updateSelectedData(key, value) {
                this.structureData[key] = value ? value : null;
                if (value) {
                    this.errors[key] = '';
                }
            },

            resetForm() {
                this.initializeSelect2();
                this.structureData = @json($structureForm ?? []);
                this.errors = {};

            },
        });
    </script>
@endscript