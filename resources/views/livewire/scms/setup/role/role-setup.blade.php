<div x-data="{ drawer: @entangle('drawer') }">
    <x-header class="" title="Role Setup">
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" link="{{ route('setup.role.create') }}" responsive icon="o-plus"
                    class="btn-primary btn-sm" />
            </div>
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$roles" :sort-by="$sortBy">
            @scope('cell_action', $role)
                <div class="flex text-sm">
                    <x-button icon="o-pencil" class="btn-ghost btn-sm text-indigo-500" tooltip-bottom="Edit"
                        link="{{ route('setup.role.create', ['id' => $role->id]) }}" x-cloak />

                    <x-button icon="o-trash" spinner class="btn-ghost btn-sm text-red-500" tooltip-bottom="Delete"
                        @click.prevent="$wire.drawer = true" x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __('Are you sure') }}" class="backdrop-blur">
        <x-card separator progress-indicator="deleteRole">
            <h3 class="text-red-500">Are you sure you want to delete this role?</h3>
            <x-slot:actions>
                <x-button label="Cancel" @click.prevent="$wire.drawer = false" />
                <x-button label="Delete" spinner="deleteRole" type="submit" class="btn-error" />
            </x-slot:actions>
        </x-card>

    </x-modal>
</div>
