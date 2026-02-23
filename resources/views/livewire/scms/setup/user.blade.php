<div x-data="{ drawer: @entangle('drawer') }">
    <x-header class="text-lg" title="User Setup">
        <x-slot:actions>
            <div x-cloak>
                <x-button label="Add" icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5"
                    @click="$wire.drawer = true" />
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-table class="text-xs" :headers="$headers" :rows="$users" :sort-by="$sortBy">
            @scope('cell_action', $user)
                <div class="flex text-xs">
                    <x-button icon="o-pencil" class="btn-ghost btn-xs text-indigo-500" tooltip-bottom="Edit"
                        @click.prevent="$wire.editPermission({{ $user->id }})" x-cloak />

                    <x-button icon="o-trash" spinner class="btn-ghost btn-xs text-red-500" tooltip-bottom="Delete"
                        @click.prevent="$wire.deletePermission({{ $user->id }})" x-cloak />

                </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs">
        <x-card separator progress-indicator="saveUser">
            <x-form no-separator wire:submit.prevent="saveUser"
                class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-3">
                    <div class="text-xs">
                        <label for="username" class="font-semibold">Username:</label>
                        <input id="username" wire:model="userForm.username" placeholder="Enter Username"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full" />
                        @error('userForm.username')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-xs">
                        <label class="text-xs font-medium text-gray-700">User Type</label>

                        <select wire:model="userForm.user_type"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full">

                            @foreach ($user_types as $option)
                                <option value="{{ $option->name }}">
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('userForm.user_type')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-xs">
                        <label for="user" class="font-semibold">User Name:</label>
                        <input id="user" wire:model="userForm.user_id" placeholder="Enter User Name"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full" />
                        @error('userForm.user_id')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-xs">
                        <label for="role" class="font-semibold">Role:</label>
                        <input id="role" wire:model="userForm.role_id" placeholder="Enter Role"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full" />
                        @error('userForm.role_id')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-xs">
                        <label for="password" class="font-semibold">Password:</label>
                        <input id="password" wire:model="userForm.password" placeholder="Enter Password"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full" />
                        @error('userForm.password')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-xs">
                        <label class="text-xs font-medium text-gray-700">Status</label>

                        <select wire:model="userForm.status"
                            class="px-2 py-1 mt-2 border border-gray-700 outline-none rounded-md w-full">

                            @foreach ($status as $option)
                                <option value="{{ $option->name }}">
                                    {{ $option->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-slot:actions>
                        <x-button label="Cancel"
                            @click.prevent="$wire.drawer = false, $wire.resetForm(), $wire.resetFormValidation()"
                            class="btn-sm" />
                        <x-button label="Save" spinner="saveUser" type="submit" class="btn-primary btn-sm" />
                    </x-slot:actions>
                </div>
            </x-form>

        </x-card>
    </x-modal>
</div>
