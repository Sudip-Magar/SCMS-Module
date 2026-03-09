<div>
    <x-header class="text-lg header" title="{{ __($title) }}" />

    <x-modal wire:model="admissionNumberingModal" class="backdrop-blur text-xs" box-class="w-100">
        <x-select label="{{ __('Admission Numbering') }}" :options="$documentNumberings" option-label="admission_no"
            option-value="admission_numbering_id" x-model="$store.studentSetup.selectedNumbering"
            @change="$store.studentSetup.updateNumbering($event.target.value)" />
        <x-slot:actions>
            <x-button label="{{ __('Ok') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                @click.prevent="$wire.admissionNumberingModal = false" />
        </x-slot:actions>
    </x-modal>

    <x-card separator progress-indicator="saveStudent">
        <x-form no-separator @submit.prevent="$store.studentSetup.saveStudent"
            class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">
            {{-- Admission Detail --}}
            <h2 class="text-sm font-semibold border-b pb-1 mb-2">{{ __('Admission Information') }}</h2>
            <div class="grid grid-cols-4 gap-3">
                <x-input label="{{ __('Admission No.') }}" x-model="$store.studentSetup.studentData.admission_no"
                    readonly />

                <div>
                    <label for="admission_date_np"
                        class="fieldset-legend mb-0.5">{{ __('Admission Date (NP)') }}:</label>
                    <input x-model="$store.studentSetup.studentData.admission_date_np" id="admission_date_np"
                        autocomplete="off" class="input nepali-date" data-sync="admission_date"
                        placeholder="{{ __('Admission Date (NP)') }}" x-cloak>
                    <span class="text-red-500" x-text="$store.studentSetup.errors.admission_date_np || ''"></span>

                </div>

                <div>
                    <label for="admission_date_en"
                        class="fieldset-legend mb-0.5">{{ __('Admission Date (EN)') }}:</label>
                    <input class="input english-date" data-sync="admission_date" type="date" id="admission_date_en"
                        placeholder="{{ __('Admission date (EN)') }}"
                        x-model="$store.studentSetup.studentData.admission_date_en">
                    <span class="text-red-500" x-text="$store.studentSetup.errors.admission_date_en"></span>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-medium" for="academic_structure_id">{{ __('Academic Structure') }}: </label>
                    <div class="flex flex-col " wire:ignore>
                        <select class="academic-structure-select"
                            @change.prevent="$store.studentSetup.updateSelectedData('academic_structure_id',$event.target.value)"
                            x-bind:id="'academic_structure_id'" x-bind:data-row-index="'academic_structure_id'"
                            x-model="$store.studentSetup.structureForm.academic_structure_id">
                            <option value="">{{ 'Add Academic Structure' }}</option>
                            <template
                                x-for="listItem in $store.studentSetup.academicStructures.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                :key="listItem.id">
                                <option :value="listItem.id" x-text="listItem.text"
                                    :selected="listItem.id === $store.studentSetup?.structureForm?.academic_structure_id ||
                                        null">
                                </option>
                            </template>
                        </select>

                        <span x-text="$store.studentSetup.errors?.academic_structure_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>
                </div>

                <div>
                    <x-input type="number" min="1" label="{{ __('Registration No.') }}"
                        placeholder="{{ __('Registration No.') }}"
                        x-model="$store.studentSetup.structureForm.registration_no" />
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.registration_no ?? ''"></span>
                </div>

                <div>
                    <x-input type="number" min="1" label="{{ __('Symbol No.') }}"
                        placeholder="{{ __('Symbol No.') }}" x-model="$store.studentSetup.structureForm.symbol_no" />
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.symbol_no ?? ''"></span>
                </div>

                <div>
                    <x-input type="number" min="1" label="{{ __('Roll No.') }}"
                        placeholder="{{ __('Roll No.') }}" x-model="$store.studentSetup.structureForm.roll_no" />
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.roll_no ?? ''"></span>
                </div>
            </div>
            {{-- End Admission Detail --}}

            {{-- Student Detail --}}
            <h2 class="text-sm font-semibold border-b pb-1 my-2">{{ __('Student Information') }}</h2>
            <div class="grid grid-cols-9 gap-3">
                <div class="col-span-8 grid grid-cols-4 gap-3">
                    <div>
                        <x-input label="{{ __('First Name') }}" placeholder="{{ __('First Name') }}"
                            x-model="$store.studentSetup.studentData.first_name" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.first_name || ''"></span>
                    </div>

                    <div>
                        <x-input label="{{ __('Middle Name') }}" placeholder="{{ __('Middle Name') }}"
                            x-model="$store.studentSetup.studentData.middle_name" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.middle_name || ''"></span>
                    </div>

                    <div>
                        <x-input label="{{ __('Last Name') }}" placeholder="{{ __('Last Name') }}"
                            x-model="$store.studentSetup.studentData.last_name" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.last_name || ''"></span>
                    </div>

                    <x-select label="{{ __('Gender') }}:" x-model="$store.studentSetup.studentData.gender"
                        :options="$gender" option-value="value" option-label="label" />

                    <div>
                        <x-input label="{{ __('Last Name') }}" placeholder="{{ __('Last Name') }}"
                            x-model="$store.studentSetup.studentData.last_name" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.last_name || ''"></span>
                    </div>

                    <div>
                        <label for="date_of_birth_np"
                            class="fieldset-legend mb-0.5">{{ __('Date of Birth (NP)') }}:</label>
                        <input x-model="$store.studentSetup.studentData.date_of_birth_np" id="date_of_birth_np"
                            autocomplete="off" class="input nepali-date" data-sync="date_of_birth"
                            placeholder="{{ __('Date of Birth (NP)') }}" x-cloak>
                        <span class="text-red-500" x-text="$store.studentSetup.errors.date_of_birth_np || ''"></span>

                    </div>

                    <div>
                        <label for="date_of_birth_en"
                            class="fieldset-legend mb-0.5">{{ __('Date of Birth (EN)') }}:</label>
                        <input class="input english-date" data-sync="date_of_birth" type="date"
                            id="date_of_birth_en" placeholder="{{ __('Date of Birth (EN)') }}"
                            x-model="$store.studentSetup.studentData.date_of_birth_en">
                        <span class="text-red-500" x-text="$store.studentSetup.errors.date_of_birth_en"></span>
                    </div>

                    <div>
                        <x-input label="{{ __('Email') }}" placeholder="{{ __('Email') }}"
                            x-model="$store.studentSetup.studentData.email" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.email || ''"></span>
                    </div>

                    <div>
                        <x-input label="{{ __('Phone') }}" placeholder="{{ __('Phone') }}"
                            x-model="$store.studentSetup.studentData.phone" />
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.phone || ''"></span>
                    </div>
                </div>

                <div class="text-center py-2 ">
                    <div
                        class="w-25 h-25 bg-gray-100 dark:bg-gray-800 mx-auto mb-2 flex items-center justify-center rounded-lg overflow-hidden">
                        @if ($studentForm->photo)
                            <img class="w-full h-full object-cover" src="{{ $studentForm->photo->temporaryUrl() }}"
                                alt="No Photo">
                        @elseif($studentForm->old_photo)
                            <img class="w-full h-full object-cover"
                                src="{{ asset('/storage/' . $studentForm->old_photo) }}"
                                alt="{{ $studentForm->first_name }}">
                        @else
                            <span> {{ __('No Photo') }}</span>
                        @endif
                    </div>

                    <div>
                        <label for="image"
                            class="bg-emerald-100 text-emerald-700 px-2 py-1 inline-block rounded-md cursor-pointer hover:bg-emerald-200 hover:text-emerald-800 transition-all duration-200">{{ __('Upload Image') }}</label>
                        <input type="file" id="image" wire:model="studentForm.photo" hidden />
                    </div>
                </div>
            </div>
            {{-- Student Detail --}}

            {{-- Address --}}
            <h2 class="text-sm font-semibold border-b pb-1 my-2">{{ __('Address') }}</h2>
            <div class="grid grid-cols-4 gap-3">

                <div class="flex flex-col gap-1.5">
                    <label class="font-medium" for="province_id">{{ __('Province') }}: </label>
                    <div class="flex flex-col " wire:ignore>
                        <select class="province-select"
                            @change.prevent="$store.studentSetup.updateSelectedData('province_id',$event.target.value)"
                            x-bind:id="'province_id'" x-bind:data-row-index="'province_id'"
                            x-model="$store.studentSetup.studentData.province_id">
                            <option value="">{{ 'Add Province' }}</option>
                            <template
                                x-for="listItem in $store.studentSetup.provinces.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                :key="listItem.id">
                                <option :value="listItem.id" x-text="listItem.text"
                                    :selected="listItem.id === $store.studentSetup?.studentData?.province_id ||
                                        null">
                                </option>
                            </template>
                        </select>

                        <span x-text="$store.studentSetup.errors?.province_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="font-medium" for="district_id">{{ __('District') }}: </label>
                    <div class="flex flex-col " wire:ignore>
                        <select class="district-select"
                            @change.prevent="$store.studentSetup.updateSelectedData('district_id',$event.target.value)"
                            x-bind:id="'district_id'" x-bind:data-row-index="'district_id'"
                            x-model="$store.studentSetup.studentData.district_id">
                            <option value="">{{ 'Add District' }}</option>
                            <template
                                x-for="listItem in $store.studentSetup.districts.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                :key="listItem.id">
                                <option :value="listItem.id" x-text="listItem.text"
                                    :selected="listItem.id === $store.studentSetup?.studentData?.district_id ||
                                        null">
                                </option>
                            </template>
                        </select>

                        <span x-text="$store.studentSetup.errors?.district_id || ''"
                            class="text-red-500 text-xs"></span>
                    </div>
                </div>

                <div>
                    <x-input label="{{ __('City') }}" placeholder="{{ __('City') }}"
                        x-model="$store.studentSetup.studentData.city" />
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.city || ''"></span>
                </div>

                <div>
                    <x-input label="{{ __('Ward No') }}" placeholder="{{ __('Ward No') }}"
                        x-model="$store.studentSetup.studentData.ward_no" />
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.ward_no || ''"></span>
                </div>
            </div>
            {{-- End Address --}}

            {{-- guardians and documents detail tabs --}}
            <h2 class="text-sm font-semibold border-b pb-1 my-2">{{ __('Guardian and Docuemnt Information') }}</h2>
            <x-tabs wire:model="selectedTab" active-class="bg-emerald-100 py-1 text-emerald-800 rounded-md">
                <x-tab name="guardian-tab" label="{{ __('Guardian Detail') }}">
                    <x-student.student-guardians alpine_store="$store.studentSetup" :relations="$relations"
                        :occupations="$occupations" />
                </x-tab>

                <x-tab name="document-tab" label="{{ __('Education Document') }}">
                    <div>Document</div>
                </x-tab>
            </x-tabs>
            {{-- end guardians and documents detail tabs --}}

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" link="{{ route('student-setup.student-list') }}"
                    class="btn-xs py-3.5 px-3.5" />
                <x-button label="{{ __('Save') }}" spinner="saveStudent" type="submit"
                    class="btn-primary btn-xs py-3.5 px-3.5" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>

@script
    <script>
        Alpine.store('studentSetup', {
            studentData: @json($studentForm ?? []),
            structureForm: @json($structureForm ?? []),
            guardianForm: [{
                student_id: '',
                name: '',
                relation: @json(\App\Enums\StudentGuardainRelationState::MOTHER->name),
                phone: '',
                occupation: @json(\App\Enums\StudentGuardianOccupationState::TEACHER->name)

            }],
            errors: {},
            provinces: @json($provinces ?? []),
            academicStructures: @json($academicStructures ?? []),
            select2Instances: [],
            documentNumberings: @json($documentNumberings ?? []),
            districts: @json($districts ?? []),
            selectedNumbering: null,

            init() {
                Alpine.nextTick(() => {
                    this.setupDates();
                    this.initializeSelect2();
                });

                if (!this.studentData.admission_numbering_id && this.documentNumberings.length) {
                    const first = this.documentNumberings[0];
                    this.selectedNumbering = first.value;
                    this.studentData.admission_no = first.admission_no;
                    this.studentData.admission_numbering_index = first.admission_numbering_index;
                    this.studentData.admission_numbering_id = first.admission_numbering_id;
                }

            },

            saveStudent(){
                console.log($store.studentSetup.guardianForm);
                
            },
            addRow() {
                this.guardianForm.push({
                    student_id: '',
                    name: '',
                    relation: @json(\App\Enums\StudentGuardainRelationState::MOTHER->name),
                    phone: '',
                    occupation: @json(\App\Enums\StudentGuardianOccupationState::TEACHER->name)
                })
            },

            removeRow(index) {
                this.guardianForm.splice(index, 1)
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

            initializeSelect2() {
                Alpine.nextTick(() => {
                    this.select2Configs = [{
                            selector: '.province-select',
                            key: 'province_id',
                            placeholder: 'Select province',
                            module_type: 'get_province',
                        },

                        {
                            selector: '.district-select',
                            key: 'district_id',
                            placeholder: 'Select district',
                            module_type: 'get_district',
                        },

                        {
                            selector: '.academic-structure-select',
                            key: 'academic_structure_id',
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

                const url = `{{ route('searchSelect2', ['module' => 'MODULE']) }}`
                    .replace('MODULE', config.module_type);

                element.select2({
                        placeholder: config.placeholder,
                        allowClear: true,
                        dropdownParent: element.closest('.modal').length ?
                            element.closest('.modal') : $(document.body),
                        ajax: {
                            url: url,
                            delay: 250,
                            data: params => ({
                                term: params.term,
                                selected_id: this.studentData['province_id'] || null,
                            }),
                            processResults: data => ({
                                results: data.results
                            })
                        }
                    })
                    .on('select2:select', (event) => {
                        this.updateSelectedData(config.key, event.params.data.id);
                    })
                    .on('select2:clear', () => {
                        this.updateSelectedData(config.key, null);
                    });
            },

            updateSelectedData(key, value) {
                if (key == 'academic_structure_id') {
                    this.structureForm[key] = value ? value : null;
                } else {
                    this.studentData[key] = value ? value : null;
                }
                console.log(this.structureForm);

                if (value) {
                    this.errors[key] = '';
                }
            },

            updateNumbering(id) {
                this.selectedNumbering = id;

                $wire.updateNumbering(id)
                    .then((response) => {
                        console.log(response);
                        this.studentData.admission_no = response.admission_no;
                        this.studentData.admission_numbering_index = response.admission_numbering_index;
                        this.studentData.admission_numbering_id = response.admission_numbering_id;

                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        });
    </script>
@endscript
