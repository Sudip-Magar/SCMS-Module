<div>
    <x-header title="Add Role" separator />

    <x-card>
        <x-form wire:submit.prevent="saveRole">

            <x-input label="Role Name" wire:model.live="name" placeholder="Enter Role Name" icon="o-user-group" />

            <h1 class="text-sm font-semibold mt-4 mb-2">
                Permissions
            </h1>

            @foreach ($grouped as $package => $subs)
                <div x-data="{ open: false }" class="mb-5">
                    <!-- Package Header -->
                    <div @click="open = !open"
                        class="cursor-pointer flex justify-between items-center p-3 bg-gray-100 rounded shadow-sm">
                        <h2 class="font-bold text-md text-primary">{{ strtoupper($package) }}</h2>
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>

                    <!-- Sub-package Table -->
                    <div x-show="open" x-transition class="overflow-x-auto mt-2">
                        <x-card shadow>
                            <table class="table table-zebra w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left">Sub Package</th>
                                        <th>Create</th>
                                        <th>Read</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subs as $sub => $actions)
                                        <tr>
                                            <td class="font-medium">{{ ucfirst($sub) }}</td>
                                            @foreach (['create', 'read', 'edit', 'delete'] as $action)
                                                <td class="text-center">
                                                    @if (isset($actions[$action]))
                                                        <x-checkbox wire:model="selectedPermissions" :value="(int) $actions[$action]"
                                                            color="primary" />
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </x-card>
                    </div>
                </div>
            @endforeach

            <x-slot:actions>
                <x-button label="Cancel" link="{{ route('setup.role') }}" />
                <x-button label="Save Role" class="btn-primary" spinner="saveRole" type="submit" />
            </x-slot:actions>

        </x-form>
    </x-card>
</div>
