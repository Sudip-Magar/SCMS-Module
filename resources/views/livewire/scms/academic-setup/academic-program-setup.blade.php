<div x-data="{ drawer: @entangle('drawer'), deleteModal: @entangle('deleteModal') }" x-init="$store.academicYearSetup.init()">
    <x-header class="text-lg header" title="{{ __('Academic Program Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                clearable />
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')"
                    @click.prevent="$wire.drawer = true, $wire.resetFormValidation(), $store.academicYearSetup.resetForm()"
                    responsive icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5" />
            </div>
        </x-slot:actions>
    </x-header>
    <x-card class="text-xs">
        <x-pagination-filter />
        <x-table class="text-xs" :headers="$headers" :rows="$program_data_list" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $program_data)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" spinner="edit({{ $program_data->id }})"
                        class="btn-ghost  btn-xs text-indigo-500" tooltip-bottom="{{ __('Edit') }}" x-cloak
                        @click.prevent="$store.academicYearSetup.edit({{ $program_data->id }})" />

                    <x-button icon="o-trash"
                        @click.prevent="$store.academicYearSetup.deleteDate({{ $program_data->id }}); $wire.deleteModal = true"
                        class="btn-ghost btn-xs text-red-500" tooltip-bottom="{{ __('Delete') }}" x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- <x-modal wire:model="deleteModal" title="{{ __('Are you sure?') }}" box-class="w-120">
        <p class="text-red-500 border-b pb-2">
            {{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                @click.prevent="$store.academicYearSetup.resetDeleteData(); $wire.deleteModal = false;" />

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                @click.prevent="$wire.delete($store.academicYearSetup.academic_year_id)" />
        </x-slot:actions>
    </x-modal> --}}

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs">
        <x-card separator progress-indicator="saveAcademicProgram">
            <x-form no-separator wire:submit="saveAcademicProgram"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-4">
                    <x-input label="{{ __('Enter Program name') }}:" placeholder="{{ __('Enter Program name') }}" wire:model="programForm.name" />

                    <x-input label="{{ __('Enter Program Short name') }}:"
                        placeholder="{{ __('Enter Program name') }}" wire:model='programForm.short_name' />

                    <div>
                        <x-select label="{{ __('Status') }}:"
                           wire:model='programForm.status' :options="$status"
                            option-value="value" option-label="label" />
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}"
                        @click.prevent="$wire.drawer = false, $store.academicYearSetup.resetForm()"
                        class="btn-xs py-3.5 px-3.5" />
                    <x-button label="{{ __('Save') }}" spinner="saveAcademicProgram" type="submit"
                        class="btn-primary btn-xs py-3.5 px-3.5" />
                </x-slot:actions>

            </x-form>

        </x-card>
    </x-modal>
</div>
@script
    <script>
        Alpine.store('academicYearSetup', {
            program_id: null,
            deleteDate(id) {
                this.program_id = id;
            },

            resetDeleteData() {
                this.program_id = null


            },
        });
    </script>
@endscript
