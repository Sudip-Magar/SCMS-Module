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
        <x-table :headers="$headers" :rows="$years" :sort-by="$sortBy">
            @scope('cell_action', $year)
                <div class="flex text-sm">
                    <x-button icon="o-pencil" spinner="edit({{ $year->id }})" class="btn-ghost btn-sm text-indigo-500"
                        tooltip-bottom="Edit" x-cloak wire:click="edit({{ $year->id }})" />

                    <x-button icon="o-trash" spinner="delete" class="btn-ghost btn-sm text-red-500" tooltip-bottom="Delete"
                        x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur">
        <x-card separator progress-indicator="savePermission">
            <x-form no-separator wire:submit.prevent="savePermission">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p>
                            <input type="text" id="nepali-datepicker" placeholder="Select Nepali Date" />
                        </p>
                    </div>

                    {{-- Start-Year --}}
                    <div>
                        {{-- <x-datepicker label="Alt" wire:model="yearForm.start_year" icon="o-calendar"
                            :config="$yearSelect" id="start_date" /> --}}

                    </div>

                    <!-- End-year -->
                    <div>
                        {{-- <x-datetime label="End Year:" placeholder="Enter End Year" wire:model="yearForm.end_year" /> --}}

                        {{-- <x-input label="End Year:" wire:model="yearForm.end_year" placeholder="Enter End Year"/>

                        @error('userForm.password')
                            <small class="text-red-500 text-xs">{{ $message }}</small>
                        @enderror --}}
                    </div>

                    <!-- Status -->
                    <div>
                        <x-select label="Status:" wire:model="yearForm.status" placeholder="Select Status"
                            :options="$status" option-value="value" option-label="label" />
                    </div>

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
