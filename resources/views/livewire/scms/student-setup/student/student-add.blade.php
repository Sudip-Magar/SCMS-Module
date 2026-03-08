<div>
    <x-header class="text-lg header" title="{{ __($title) }}"/>

    <x-card separator progress-indicator="saveStudent">
        <x-form no-separator @submit="$store.studentSetup.saveStudent"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">
            {{-- Student Detail--}}
            <h2 class="text-sm font-semibold border-b pb-1 mb-2">{{__('Student Information')}}</h2>
            <div class="grid grid-cols-9 gap-3">
                <div class="col-span-8 grid grid-cols-4 gap-3">
                    <div>
                        <x-input label="{{__('First Name')}}" placeholder="{{__('First Name')}}"
                                 x-model="$store.studentSetup.studentData.first_name"/>
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.first_name || ''"></span>
                    </div>

                    <div>
                        <x-input label="{{__('Middle Name')}}" placeholder="{{__('Middle Name')}}"
                                 x-model="$store.studentSetup.studentData.middle_name"/>
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.middle_name || ''"></span>
                    </div>

                    <div>
                        <x-input label="{{__('Last Name')}}" placeholder="{{__('Last Name')}}"
                                 x-model="$store.studentSetup.studentData.last_name"/>
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.last_name || ''"></span>
                    </div>

                    <x-select label="{{ __('Gender') }}:" x-model="$store.studentSetup.studentData.gender"
                              :options="$gender" option-value="value" option-label="label"/>

                    <div>
                        <x-input label="{{__('Last Name')}}" placeholder="{{__('Last Name')}}"
                                 x-model="$store.studentSetup.studentData.last_name"/>
                        <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.last_name || ''"></span>
                    </div>

                    <div>
                        <label for="date_of_birth_np"
                               class="fieldset-legend mb-0.5">{{ __('Date of Birth (NP)') }}:</label>
                        <input x-model="$store.studentSetup.studentData.date_of_birth_np" id="date_of_birth_np"
                               autocomplete="off" class="input nepali-date" data-sync="date_of_birth"
                               placeholder="{{ __('Enter Start Year (NP)') }}" x-cloak>
                        <span class="text-red-500" x-text="$store.studentSetup.errors.date_of_birth_np || ''"></span>

                    </div>

                    <div>
                        <label for="date_of_birth_en"
                               class="fieldset-legend mb-0.5">{{ __('Date of Birth (EN)') }}:</label>
                        <input class="input english-date" data-sync="date_of_birth" type="date" id="date_of_birth_en"
                               placeholder="{{ __('Date of Birth (EN)') }}"
                               x-model="$store.studentSetup.studentData.date_of_birth_en">
                        <span class="text-red-500" x-text="$store.studentSetup.errors.start_year_en"></span>
                    </div>

                     <div>
                    <x-input label="{{__('Email')}}" placeholder="{{__('Email')}}"
                             x-model="$store.studentSetup.studentData.email"/>
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.email || ''"></span>
                </div>

                 <div>
                    <x-input label="{{__('Phone')}}" placeholder="{{__('Phone')}}"
                             x-model="$store.studentSetup.studentData.phone"/>
                    <span class="text-red-500 text-xs" x-text="$store.studentSetup.errors.phone || ''"></span>
                </div>
                </div>

                <div class="text-center py-2 ">
                    <div
                        class="w-25 h-25 bg-gray-100 mx-auto mb-2 flex items-center justify-center rounded-lg overflow-hidden">
                        @if($studentForm->photo)
                            <img class="w-full h-full object-cover" src="{{ $studentForm->photo->temporaryUrl() }}"
                                 alt="No Photo">
                        @elseif($studentForm->old_photo)
                            <img class="w-full h-full object-cover"
                                 src="{{ asset('/storage/'. $studentForm->old_photo) }}"
                                 alt="{{ $studentForm->first_name }}">
                        @else
                            <span> {{__('No Photo')}}</span>
                        @endif
                    </div>

                    <div>
                        <label for="image"
                               class="bg-emerald-100 text-emerald-700 px-2 py-1 inline-block rounded-md cursor-pointer hover:bg-emerald-200 hover:text-emerald-800 transition-all duration-200">{{__('Upload Image')}}</label>
                        <input type="file" id="image" wire:model="studentForm.photo" hidden/>
                    </div>
                </div>
            </div>
            {{-- Student Detail--}}
            <h2 class="text-sm font-semibold border-b pb-1 mb-2">{{__('Address')}}</h2>
            <div class="grid grid-cols-4 gap-3">

            </div>
        </x-form>
    </x-card>
</div>

@script
<script>
    Alpine.store('studentSetup', {
        studentData: @json($studentForm ?? []),
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
    });
</script>
@endscript

