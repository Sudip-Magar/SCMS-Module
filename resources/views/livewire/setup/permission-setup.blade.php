<div x-data="{ drawer: @entangle('drawer') }">
    <x-header class="" title="Role Setup">
        <x-slot:actions>
            <div x-cloak>
                <x-button :label="__('Add')" @click.prevent="$wire.drawer = true" responsive icon="o-plus"
                    class="btn-primary btn-sm" />
            </div>
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$permission" :sort-by="$sortBy" />
    </x-card>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur">
        <x-card separator progress-indicator="savePermission">
            <x-form no-separator wire:submit.prevent="savePermission">
                <x-input label="Permission:" placeholder="Enter Permission" wire:model.live="package" />
                <p class="text-red-500 text-sm">
                    It will create the following permissions:
                   <ul class="list-disc mt-0 pt-0 text-sm">
                     @foreach ($actions as $action)
                        <li class="text-red-500">{{ $action }}</li>
                    @endforeach
                   </ul>
                </p>

                <x-slot:actions>
                    <x-button label="Cancel" @click.prevent="$wire.drawer = false" class="btn-sm" />
                    <x-button label="Add" type="submit" class="btn-primary btn-sm" spinner="savePermission" />
                </x-slot:actions>
            </x-form>
        </x-card>
    </x-modal>
</div>
