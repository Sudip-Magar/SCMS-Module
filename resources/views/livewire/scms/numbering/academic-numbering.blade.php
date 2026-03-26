<div
    x-data="{ drawer: @entangle('drawer'), deleteModal: @entangle('deleteModal'), allowedPermissions: @js($allowedPermissions) }">

    <x-header class="text-lg header" title="{{ __('Academic Numbering') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                     clearable/>
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak x-show="allowedPermissions.create">
                <x-button :label="__('Add')"
                          @click.prevent="$wire.drawer = true, $wire.resetFormValidation(), $store.numberingSetup.resetForm()"
                          responsive icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5"/>
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-pagination-filter/>
        <x-table class="text-xs" :headers="$headers" :rows="$numbering_data_list" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $numbering_data)
            <div class="flex text-xs">
                <div x-cloak x-show="allowedPermissions.edit">
                    <x-button icon="o-pencil" spinner="edit({{ $numbering_data->id }})"
                              class="btn-ghost btn-xs text-indigo-500" tooltip-bottom="{{ __('Edit') }}"
                              wire:click="edit({{ $numbering_data->id }})"/>
                </div>

                <div x-cloak x-show="allowedPermissions.delete">
                    <x-button icon="o-trash" class="btn-ghost btn-xs text-red-500" tooltip-bottom="{{ __('Delete') }}"
                              @click.prevent="$store.numberingSetup.deleteData({{ $numbering_data->id }}); $wire.deleteModal = true"/>
                </div>
            </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="deleteModal" title="{{ __('Are you sure?') }}" box-class="w-120">
        <p class="text-red-500 border-b pb-2">
            {{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                      @click.prevent="$store.numberingSetup.resetDeleteData(); $wire.deleteModal = false;"/>

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                      @click.prevent="$wire.delete($store.numberingSetup.numbering_id)"/>
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="drawer" title="{{ __(key: $title) }}" class="backdrop-blur text-xs">
        <x-card seperator progress-indicator="saveAdmissionNumbering">
            <x-form @submit.prevent="$store.numberingSetup.saveAdmissionNumbering"
                    class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-select label="{{ __('Academic Level') }}:"
                                  x-model="$store.numberingSetup.numberingForm.academic_level"
                                  :options="$academic_level"
                                  option-value="value" option-label="label"/>
                        <span class="text-red-500 text-xs"
                              x-text="$store.numberingSetup.errors.academic_level || ''"></span>
                    </div>

                    <x-input label="{{ __('Prefix') }}" placeholder="{{ __('Prefix') }}"
                             x-model="$store.numberingSetup.numberingForm.prefix"/>
                    <x-input label="{{ __('Suffix') }}" placeholder="{{ __('Suffix') }}"
                             x-model="$store.numberingSetup.numberingForm.suffix"/>
                    <div>
                        <x-input type="number" label="{{ __('Start') }}" placeholder="{{ __('Start') }}"
                                 x-model="$store.numberingSetup.numberingForm.start"/>
                        <span class="text-red-500 text-xs" x-text="$store.numberingSetup.errors.start || ''"></span>
                    </div>
                    <x-input type="number" label="{{ __('Current') }}" placeholder="{{ __('Current') }}"
                             x-model="$store.numberingSetup.numberingForm.current"/>
                    <x-input type="number" label="{{ __('Body Length') }}" placeholder="{{ __('Body Length') }}"
                             x-model="$store.numberingSetup.numberingForm.body_length"/>
                    <x-input type="number" label="{{__('Total Length')}}"
                             x-bind:value="$store.numberingSetup.total_length"
                             readonly/>

                    <x-select label="{{ __('Status') }}:" x-model="$store.numberingSetup.numberingForm.status"
                              :options="$status" option-value="value" option-label="label"/>
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}"
                              @click.prevent="$wire.drawer = false, $wire.resetFormValidation()"
                              class="btn-xs py-3.5 px-3.5"/>
                    <x-button label="{{ __('Save') }}" spinner="saveAdmissionNumbering" type="submit"
                              class="btn-primary btn-xs py-3.5 px-3.5"/>
                </x-slot:actions>
            </x-form>
        </x-card>
    </x-modal>
</div>

@script
<script>
    Alpine.store('numberingSetup', {
        numberingForm: @json($numberingForm ?? []),
        errors: {},
        numbering_id: null,
        init(numbering) {
            if (numbering) {
                this.numberingForm = numbering;
            }
        },

        get total_length() {
            return Number(this.numberingForm.body_length || 0) +
                (this.numberingForm.prefix?.length || 0) +
                (this.numberingForm.suffix?.length || 0);
        },

        saveAdmissionNumbering() {
            $store.numberingSetup.numberingForm.total_length = $store.numberingSetup.total_length;
            $wire.saveAdmissionNumbering($store.numberingSetup.numberingForm).then((response) => {
                this.errors = {};

                if (response?.original?.errors) {
                    for (let field in response.original.errors) {
                        $store.numberingSetup.errors[field] = response.original.errors[field][
                            0
                            ];
                    }
                    return false;
                }
            }).then((error) => {
                console.log(error);

            })
        },

        resetForm() {
            this.numberingForm = @json($numberingForm ?? []);
            this.errors = {};
        },

        deleteData(id) {
            this.numbering_id = id;

        },

        resetDeleteData() {
            this.numbering_id = null
        },
    })
</script>
@endscript
