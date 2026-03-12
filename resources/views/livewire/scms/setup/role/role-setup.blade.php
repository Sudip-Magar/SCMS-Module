<div x-data="{ drawer: @entangle('drawer') }">
    <x-header class="text-lg header" title="{{ __('Role Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                     clearable/>
        </x-slot:middle>
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" link="{{ route('setup.role.create') }}" responsive icon="o-plus"
                          class="btn-primary btn-xs p-3.5"/>
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-pagination-filter/>
        <x-table class="text-xs" :headers="$headers" :rows="$roles" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $role)
            <div class="flex text-sm">
                <x-button icon="o-pencil" class="btn-ghost btn-xs text-indigo-500" tooltip-bottom="{{ __('Edit') }}"
                          link="{{ route('setup.role.create', ['id' => $role->id]) }}" x-cloak/>

                <x-button icon="o-trash" spinner=" " class="btn-ghost btn-xs text-red-500"
                          tooltip-bottom="{{ __('Delete') }}"
                          @click.prevent="$store.roleSetup.deleteData({{$role['id']}}); $wire.drawer = true" x-cloak/>

            </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __('Are you sure?') }}" class="backdrop-blur" box-class="w-120">
        <x-card separator progress-indicator="deleteRole">
            <h3 class="text-red-500">{{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</h3>
            <x-slot:actions>
                <x-button label="{{ __(key: 'Cancel') }}" @click.prevent="$wire.drawer = false; $store.roleSetup.resetDeleteData()" class="btn-xs p-3.5"/>
                <x-button label="{{ __('Delete') }}" spinner="delete" @click.prevent="$wire.delete($store.roleSetup.role_id)" class="btn-error btn-xs p-3.5"/>
            </x-slot:actions>
        </x-card>

    </x-modal>
</div>

@script
    <script>
        Alpine.store('roleSetup', {
            role_id: null,

            deleteData(id){
                this.role_id = id;
            },

            resetDeleteData(){
                this.role_id = null;
            }
        })
    </script>
@endscript
