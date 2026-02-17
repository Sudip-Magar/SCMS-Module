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
            @scope('cell_action', $roles)
                <div class="flex text-sm">
                    <x-button icon="o-pencil" spinner class="btn-ghost btn-sm text-indigo-500" tooltip-bottom="Edit" x-cloak />

                    <x-button icon="o-trash" spinner class="btn-ghost btn-sm text-red-500" tooltip-bottom="Delete"
                        x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>
</div>
