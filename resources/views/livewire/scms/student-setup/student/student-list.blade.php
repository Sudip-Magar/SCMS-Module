<div x-data="{allowedPermission: @js($allowedPermission)}" x-init="console.log(allowedPermission)">
    <x-header class="text-lg header" title="{{ __('Student Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                     clearable/>
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak>
                <x-button x-show="allowedPermission.create" :label="__('Add')"
                          link="{{route('student-setup.student-add')}}" responsive
                          icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5"/>
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-pagination-filter/>
        <div class="overflow-x-auto scrollbar">
            <x-table class="max-w-full overflow-x-auto text-xs whitespace-nowrap"
                     :headers="$headers" :rows="$student_data_list" :sort-by="$sortBy" with-pagination>
                @scope('cell_action', $student_data)
                <div class="flex text-xs">
                    <div x-show="allowedPermission.edit" x-cloak>
                        <x-button icon="o-pencil"
                                  class="btn-ghost btn-xs text-indigo-500" tooltip-bottom="{{ __('Edit') }}"
                                  link="{{route('student-setup.student-add',['id' => $student_data['id'] ])}}"/>
                    </div>
                    <div x-cloak x-show="allowedPermission.delete">
                        <x-button icon="o-trash" class="btn-ghost btn-xs text-red-500"
                                  tooltip-bottom="{{ __('Delete') }}"
                                  @click.prevent="$store.studentSetup.deleteData({{$student_data['id']}}); $wire.deleteModal = true"/>
                    </div>
                </div>
                @endscope
            </x-table>
        </div>
    </x-card>

    <x-modal wire:model="deleteModal" title="{{ __('Are you sure?') }}" box-class="w-120" class="backdrop-blur text-xs">
        <p class="text-red-500 border-b pb-2">
            {{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</p>

        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                      @click.prevent="$store.studentSetup.resetDeleteData(); $wire.deleteModal = false;"/>

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                      @click.prevent="$wire.delete($store.studentSetup.student_id)"/>
        </x-slot:actions>
    </x-modal>
</div>

@script
<script>
    Alpine.store('studentSetup', {
        student_id: null,

        deleteData(id) {
            this.student_id = id
        },

        resetDeleteData() {
            this.room_id = null
        },
    })
</script>
@endscript
