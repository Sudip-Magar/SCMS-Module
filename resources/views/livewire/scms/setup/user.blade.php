<div
    x-data="{ drawer: @entangle('drawer'), deleteModal: @entangle('deleteModal'), allowedPermissions: @js($allowedPermissions) }">
    <x-header class="text-lg header" title="{{ __('User Setup') }}">
        <x-slot:middle class="flex justify-end">
            <x-input class="inline-block text-xs" placeholder="{{ __('Search...') }}" wire:model.live.debounce="search"
                     clearable/>
        </x-slot:middle>
        <x-slot:actions>
            <div x-show="allowedPermissions.create" x-cloak>
                <x-button label="{{ __('Add') }}" icon="o-plus" class="btn-primary btn-xs py-3.5 px-3.5"
                          @click="$wire.drawer = true; $store.userSetup.resetForm()"/>
            </div>
        </x-slot:actions>
    </x-header>

    <x-card class="text-xs">
        <x-pagination-filter/>
        <x-table class="text-xs" :headers="$headers" :rows="$users" :sort-by="$sortBy" with-pagination>
            @scope('cell_action', $user)
            <div class="flex text-xs">
                <div x-cloak x-show="allowedPermissions.edit">
                    <x-button icon="o-pencil" spinner="edit({{$user['userId']}})"
                              class="btn-ghost btn-xs text-indigo-500"
                              tooltip-bottom="Edit"
                              @click.prevent="$wire.edit({{ $user['userId'] }})"/>
                </div>

                <div x-cloak x-show="allowedPermissions.delete">
                    <x-button icon="o-trash" class="btn-ghost btn-xs text-red-500" tooltip-bottom="Delete"
                              @click.prevent="$store.userSetup.deleteData({{ $user['userId'] }}); $wire.deleteModal = true"
                              />
                </div>

            </div>
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="deleteModal" title="{{ __('Are you sure?') }}" box-class="w-120">
        <p class="text-red-500 border-b pb-2">
            {{ __('Are you sure you want to delete this data? this action cannot be undone!') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" class="btn-primary btn-xs py-3.5 px-3.5"
                      @click.prevent="$store.userSetup.resetDeleteData(); $wire.deleteModal = false;"/>

            <x-button label="{{ __('Delete') }}" spinner="delete" class="btn-error btn-xs py-3.5 px-3.5"
                      @click.prevent="$wire.delete($store.userSetup.user_id)"/>
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="drawer" title="{{ __($title) }}" class="backdrop-blur text-xs">
        <x-card separator progress-indicator="saveUser">
            <x-form no-separator @submit.prevent="$store.userSetup.saveUser"
                    class="reset-grid reset-grid-flow-row reset-auto-rows-min reset-gap-3 ">

                <div class="grid grid-cols-2 gap-3">
                    <div class="text-xs">
                        <x-input label="{{ __('Username') }}" placeholder="{{ __('Username') }}" suffix="@milton.com"
                                 x-model="$store.userSetup.userForm.username"/>
                        <span class="text-red-500 text-xs" x-text="$store.userSetup.errors.username || ''"></span>
                    </div>

                    <div class="text-xs">
                        <x-select label="{{ __('User Type') }}:"
                                  x-model="$store.userSetup.userForm.profile_type" :options="$profile_types"
                                  option-value="value" option-label="label"/>
                        <template x-if="$store.userSetup.errors?.profile_type">
                            <small class="text-red-500" x-text="$store.userSetup.errors.profile_type || ''"></small>
                        </template>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="profile_id">{{ __('User Name') }}: </label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="profile-select"
                                    @change.prevent="$store.userSetup.updateSelectedData('profile_id',$event.target.value)"
                                    x-bind:id="'profile_id'" x-bind:data-row-index="'profile_id'"
                                    x-model="$store.userSetup.userForm.profile_id">
                                <option value="">{{ 'Add User Name' }}</option>
                                <template
                                    x-for="listItem in $store.userSetup.profiles.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                            :selected="listItem.id === $store.userSetup?.userForm?.profile_id ||
                                        null">
                                    </option>
                                </template>
                            </select>

                            <span x-text="$store.userSetup.errors?.profile_id || ''"
                                  class="text-red-500 text-xs"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="font-medium" for="role_id">{{ __('Select Role') }}: </label>
                        <div class="flex flex-col " wire:ignore>
                            <select class="role-select"
                                    @change.prevent="$store.userSetup.updateSelectedData('role_id',$event.target.value)"
                                    x-bind:id="'role_id'" x-bind:data-row-index="'role_id'"
                                    x-model="$store.userSetup.userForm.role_id">
                                <option value="">{{ 'Add Role' }}</option>
                                <template
                                    x-for="listItem in $store.userSetup.roles.map(listItem => JSON.parse(JSON.stringify(listItem)))"
                                    :key="listItem.id">
                                    <option :value="listItem.id" x-text="listItem.text"
                                            :selected="listItem.id === $store.userSetup?.userForm?.role_id ||
                                        null">
                                    </option>
                                </template>
                            </select>

                            <span x-text="$store.userSetup.errors?.role_id || ''"
                                  class="text-red-500 text-xs"></span>
                        </div>
                    </div>

                    <div class="text-xs">
                        <div class="text-xs">
                            <x-input label="{{ __('Password') }}" placeholder="{{ __('Password') }}"
                                     x-model="$store.userSetup.userForm.password"/>
                            <template x-if="$store.userSetup.errors?.password">
                                <span class="text-red-500 text-xs"
                                      x-text="$store.userSetup.errors?.password"></span>
                            </template>
                        </div>
                    </div>

                    <div class="text-xs">
                        <x-select label="{{ __('Status') }}:"
                                  x-model="$store.userSetup.userForm.status" :options="$status"
                                  option-value="value" option-label="label"/>
                        <template x-if="$store.userSetup.errors?.status">
                            <small class="text-red-500" x-text="$store.userSetup.errors.status"></small>
                        </template>
                    </div>

                    <x-slot:actions>
                        <x-button label="Cancel"
                                  @click.prevent="$wire.drawer = false, $store.userSetup.resetForm()"
                                  class="btn-sm"/>
                        <x-button label="Save" spinner="saveUser" type="submit" class="btn-primary btn-sm"/>
                    </x-slot:actions>
                </div>
            </x-form>

        </x-card>
    </x-modal>
</div>

@script
<script>
    Alpine.store('userSetup', {
        user_id: null,
        userForm: @json($userForm ?? []),
        errors: {},
        profiles: @json($profiles ?? []),
        roles: @json($roles ?? []),

        init(userData, role_id) {
            this.initializeSelect2();

            if (userData) {
                this.userForm = userData;
                console.log(this.userForm)
                console.log(this.userForm.role_id)
            }
        },

        initSelect2Element(config) {
            const element = $(config.selector);

            if (element.data('select2')) {
                element.select2('destroy');
            }

            const url = `{{ route('searchSelect2', ['module' => 'MODULE']) }}`
                .replace('MODULE', config.module_type);

            element.select2({
                placeholder: config.placeholder,
                allowClear: true,
                dropdownParent: element.closest('.modal').length ?
                    element.closest('.modal') : $(document.body),
                ajax: {
                    url: url,
                    delay: 250,
                    data: params => ({
                        term: params.term,
                        selected_profile_type: this.userForm['profile_type'] || null,
                    }),
                    processResults: data => ({
                        results: data.results
                    })
                }
            })
                .on('select2:select', (event) => {
                    this.updateSelectedData(config.key, event.params.data.id);
                })
                .on('select2:clear', () => {
                    this.updateSelectedData(config.key, null);
                });
        },

        initializeSelect2() {
            Alpine.nextTick(() => {
                this.select2Configs = [{
                    selector: '.profile-select',
                    key: 'profile_id',
                    placeholder: 'Select User name',
                    module_type: 'get_profile',
                }, {
                    selector: '.role-select',
                    key: 'role_id',
                    placeholder: 'Select Role',
                    module_type: 'get_role',
                },];

                this.select2Configs.forEach(config => {
                    this.initSelect2Element(config);
                });
            });
        },

        updateSelectedData(key, value) {
            this.userForm[key] = value ? value : null;

            if (value) {
                this.errors[key] = '';
            }
        },

        saveUser() {
            $wire.saveUser($store.userSetup.userForm).then((response) => {
                this.errors = {};
                if (response?.original?.errors) {
                    // Set errors for Alpine
                    for (let field in response.original.errors) {
                        $store.userSetup.errors[field] = response.original.errors[field][0];
                    }
                    console.log($store.userSetup.errors);
                    return false
                }
            }).catch((error) => {
                console.log(error)
            })
        },

        resetForm() {
            this.userForm = @json($userForm ?? []);
            this.errors = {};
        },

        deleteData(id) {
            this.user_id = id;
            console.log(id)
        },

        resetDeleteData() {
            this.user_id = null;
        },
    })
</script>
@endscript
