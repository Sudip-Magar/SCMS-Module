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
        <x-table class="text-xs" :headers="$headers" :rows="$room_data_list" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $room_data)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" spinner="edit({{ $room_data->id }})" class="btn-ghost  btn-xs text-indigo-500"
                        tooltip-bottom="{{ __('Edit') }}" x-cloak wire:click='edit({{ $room_data->id }})' />

                    <x-button icon="o-trash"
                        @click.prevent="$store.academicRoomSetup.deleteData({{ $room_data->id }}); $wire.deleteModal = true"
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
                @click.prevent="$store.academicRoomSetup.resetDeleteData(); $wire.deleteModal = false;" />

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                @click.prevent="$wire.delete($store.academicRoomSetup.room_id)" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs">
        <x-card separator progress-indicator="saveAcademicRoom">
            <x-form no-separator wire:submit="saveAcademicRoom"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-4">
                    <x-input label="{{ __('Enter Room name') }}:" placeholder="{{ __('Enter Room name') }}"
                        wire:model="roomForm.name" />

                    <x-input label="{{ __('Enter Room Short name') }}:"
                        placeholder="{{ __('Enter Room Short name') }}" wire:model='roomForm.short_name' />

                    <x-input type="number" label="{{ __('Enter Floor Number') }}:" placeholder="{{ __('Enter Floor Number') }}"
                        wire:model='roomForm.floor_no' />

                    <x-input type="number" label="{{ __('Enter Block Number') }}:" placeholder="{{ __('Enter Block Number') }}"
                        wire:model='roomForm.block_no' />

                    <div>
                        <x-select label="{{ __('Status') }}:" wire:model='roomForm.status' :options="$status"
                            option-value="value" option-label="label" />
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}"
                        @click.prevent="$wire.drawer = false, $wire.resetFormValidation()"
                        class="btn-xs py-3.5 px-3.5" />
                    <x-button label="{{ __('Save') }}" spinner="saveAcademicRoom" type="submit"
                        class="btn-primary btn-xs py-3.5 px-3.5" />
                </x-slot:actions>

            </x-form>

        </x-card>
    </x-modal>
</div>
@script
    <script>
        Alpine.store('academicRoomSetup', {
            room_id: null,
            deleteData(id) {
                this.room_id = id;
            },

            resetDeleteData() {
                this.room_id = null


            },
        });
    </script>
@endscript
