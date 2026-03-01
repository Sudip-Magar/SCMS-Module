<div>
    <x-header class="text-lg header" title="{{ __($title) }}"/>
    <x-card separator progress-indicator="saveAcademicTimetable">
        <x-form no-separator @submit.prevent="$store.academicTimetableSetup.saveAcademicTimetable"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">
            <div class="grid grid-cols-5 gap-3">
                <div>
                    <x-input label="{{ __('Enter Timetable Name') }}:"
                             placeholder="{{ __('Enter Timetable name') }}"
                             x-model="$store.academicTimetableSetup.timetableData.name"/>
                    <span x-text="$store.academicTimetableSetup.errors?.name || ''"
                          class="text-red-500 text-xs"></span>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-medium" for="structure_id">{{ __('Academic Structure') }}: </label>
                    <div class="flex flex-col " wire:ignore>
                        <select class="academic-structure-select"
                                @change.prevent="$store.academicTimetableSetup.updateSelectedData('structure_id',$event.target.value)"
                                x-bind:id="'structure_id'" x-bind:data-row-index="'structure_id'"
                                x-model="$store.academicTimetableSetup.timetableData.structure_id">
                            <option value="">{{ 'Add Program' }}</option>
                            <template
                                x-for="listItem in $store.academicTimetableSetup.academicStructures.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                :key="listItem.id">
                                <option :value="listItem.id" x-text="listItem.text"
                                        :selected="listItem.id === $store.academicTimetableSetup?.timetableData?.structure_id ||
                                            null">
                                </option>
                            </template>
                        </select>

                        <span x-text="$store.academicTimetableSetup.errors?.structure_id || ''"
                              class="text-red-500 text-xs"></span>
                    </div>
                </div>

                {{-- Academic Structure Detial --}}
                <div>
                    <x-input label="{{__('Academic Type')}}"
                             x-model="$store.academicTimetableSetup.structureData.academic_level" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Year')}}"
                             x-model="$store.academicTimetableSetup.structureData.year" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Program')}}"
                             x-model="$store.academicTimetableSetup.structureData.program" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Faculty')}}"
                             x-model="$store.academicTimetableSetup.structureData.faculty" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Level')}}"
                             x-model="$store.academicTimetableSetup.structureData.level" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Room')}}"
                             x-model="$store.academicTimetableSetup.structureData.room" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic Section')}}"
                             x-model="$store.academicTimetableSetup.structureData.section" readonly/>
                </div>

                <div>
                    <x-input label="{{__('Academic System')}}"
                             x-model="$store.academicTimetableSetup.structureData.academic_system" readonly/>
                </div>
                {{-- End Academic Structure Detial --}}
            </div>

            {{-- Timetable Detail --}}
            <div>
                <h1 class="font-bold text-sm">Timetable Detail</h1>
                <table></table>
            </div>
        </x-form>
    </x-card>

    <!-- Overlay -->
    <div x-cloak x-show="$store.academicTimetableSetup.loading" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-500 flex items-center justify-center transition-all duration-150">

        <!-- Blurred Background -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

        <!-- Modal Box -->
        <div class="relative bg-white rounded-xl shadow-xl px-8 py-6 flex items-center gap-3">

            <x-loading class="progress-primary"/>

            <!-- Text -->
            <span class="text-gray-700 font-medium">Loading...</span>

        </div>
    </div>
</div>

@script
<script>
    Alpine.store('academicTimetableSetup', {
        timetableData: @json($timetableForm ?? []),
        academicStructures: @json($academicStructures ?? []),
        loading: false,
        structureData: {},
        errors: {},

        init() {
            this.initializeSelect2();
        },

        initializeSelect2() {
            Alpine.nextTick(() => {
                this.select2Configs = [
                    {
                        selector: '.academic-structure-select',
                        key: 'structure_id',
                        placeholder: 'Select Academic Structure',
                        module_type: 'get_academic_structure',
                    },
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
            const parent = element.closest('.modal').length
                ? element.closest('.modal')
                : $(document.body);

            element.select2({
                placeholder: config.placeholder,
                allowClear: true,
                dropdownParent: parent,
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
            }).on('select2:clear', () => {
                this.updateSelectedData(config.key, null);
            });


        },

        updateSelectedData(key, value) {
            this.timetableData[key] = value ? value : null;

            if (key == 'structure_id') {
                this.loading = true;
                $wire.fetchStructureData(value).then((response) => {
                    console.log(response);
                    this.structureData.academic_level = response.structure.academic_level.charAt(0).toUpperCase() + response.structure.academic_level.slice(1).toLowerCase();
                    this.structureData.year = response.structure.year.name;
                    this.structureData.program = response.structure.program.name;
                    this.structureData.faculty = response.structure.faculty.name;
                    this.structureData.level = response.structure.level.name;
                    this.structureData.room = response.structure.room.name;
                    this.structureData.section = response.structure.section.name;
                    this.structureData.academic_system = response.structure.academic_system.charAt(0).toUpperCase() + response.structure.academic_system.slice(1).toLowerCase();
                    this.loading = false;
                    // console.log(this.structureData)
                }).catch((error) => {
                    console.log(error);
                })

            }
            if (value) {
                this.errors[key] = '';
            }
        },
    })
</script>
@endscript
