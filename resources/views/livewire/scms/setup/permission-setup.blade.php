<div x-data="{ drawer: @entangle('drawer') }">
    <x-header class="" title="Role Setup">
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" @click.prevent="$wire.drawer = true, $wire.resetFormValidation();" responsive
                    icon="o-plus" class="btn-primary btn-sm" />
            </div>
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$permissions" :sort-by="$sortBy">
            @scope('cell_action', $permission)
                <div class="flex text-sm">
                    <x-button icon="o-pencil" spinner="edit({{ $permission->id }})" class="btn-ghost btn-sm text-indigo-500"
                        tooltip-bottom="Edit" x-cloak wire:click="edit({{ $permission->id }})" />

                    <x-button icon="o-trash" spinner="delete" class="btn-ghost btn-sm text-red-500" tooltip-bottom="Delete"
                        x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur">
        <x-card separator progress-indicator="savePermission">
            <x-form no-separator wire:submit.prevent="savePermission">

                {{-- Package --}}
                <x-input label="Package" list="packageList" wire:model.live="package_name" />

                <datalist id="packageList">
                    @foreach ($packages as $pkg)
                        <option value="{{ $pkg }}">
                    @endforeach
                </datalist>

                {{-- Subpackage --}}
                <x-input label="Sub Package" list="subPackageList" wire:model.live="sub_package_name" />

                <datalist id="subPackageList">
                    @foreach ($subPackages as $sub)
                        <option value="{{ $sub }}">
                    @endforeach
                </datalist>

                {{-- Actions --}}
                <div class="grid grid-cols-2 gap-3 mt-3">
                    @if ($id)
                        @foreach ($actions as $action => $checked)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" disabled wire:model="actions.{{ $action }}">
                                {{ ucfirst($action) }}
                            </label>
                        @endforeach
                    @else
                        @foreach ($actions as $action => $checked)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="actions.{{ $action }}">
                                {{ ucfirst($action) }}
                            </label>
                        @endforeach
                    @endif
                </div>

                {{-- Preview --}}
                <div class="mt-3 text-sm">
                    <p class="font-semibold text-gray-600">Preview:</p>
                    <ul class="list-disc ml-5 text-red-500">
                        @foreach ($this->previewPermissions as $perm)
                            <li>{{ $perm }}</li>
                        @endforeach
                    </ul>
                </div>

                <x-slot:actions>
                    <x-button label="Cancel" @click.prevent="$wire.drawer = false, $wire.resetForm()" />
                    <x-button label="Save" spinner="savePermission" type="submit" class="btn-primary" />
                </x-slot:actions>

            </x-form>

        </x-card>
    </x-modal>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('permissionSetup', () => ({
            permissionId: null,

        }));
    });
</script>
