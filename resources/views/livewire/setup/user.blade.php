<div>
    <x-header title="User Setup">
        <x-slot:actions>
            <x-button label="Add User" icon="o-plus" class="btn-primary btn-sm" @click="$wire.createPermission" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('cell_action', $user)
                <div class="flex text-sm">
                    <x-button icon="o-pencil" class="btn-ghost btn-sm text-indigo-500" tooltip-bottom="Edit"
                        @click.prevent="$wire.editPermission({{ $user->id }})" x-cloak />

                    <x-button icon="o-trash" spinner class="btn-ghost btn-sm text-red-500" tooltip-bottom="Delete"
                        @click.prevent="$wire.deletePermission({{ $user->id }})" x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>
</div>
