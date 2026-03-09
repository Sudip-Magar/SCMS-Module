@props([
    'alpine_store' => '',
    'relations',
    'occupations',
])

<div>
    <div class="w-full overflow-x-auto my-3">
        <table class="min-w-full border-2 border-gray-400 border-collapse">
            <thead class="bg-emerald-600 text-white rounded-md">
                <tr>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">S.No</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Name</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Relation</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Phone</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Occupation</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Action</th>
                </tr>
            </thead>
             <tbody class="bg-white">
            <template x-for="(guardian, index) in {{ $alpine_store }}.guardianForm" :key="index">
                <tr x-show="guardian">
                    <td class="px-4 py-2 border-2 border-gray-400 w-15" x-text="index + 1"></td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                        <x-input placeholder="{{ __('Enter Guardian Name') }}"
                            x-model='guardian.name' />
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                        <x-select x-model="guardian.relation" :options="$relations"
                            option-value="value" option-label="label" />
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                        <x-input type="number" placeholder="{{ __('Enter Guardian Number') }}"
                            x-model='guardian.phone' />
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                        <x-select x-model="guardian.occupation" :options="$occupations"
                            option-value="value" option-label="label" />
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 ">
                        <x-button icon="o-plus" @click.prevent="{{ $alpine_store }}.addRow()"
                            class="btn-xs btn-success text-white" />
                        <template x-if="index > 0">
                            <x-button icon="o-minus"
                                @click.prevent="{{ $alpine_store }}.removeRow(index)"
                                class="btn-xs btn-error text-white" />
                        </template>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</div>
