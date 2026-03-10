@props([
    'alpine_store' => '',
    'document_types',
])

<div>
    <div class="w-full overflow-x-auto my-3">
        <table class="min-w-full border-2 border-gray-400 border-collapse">
            <thead class="bg-emerald-600 text-white rounded-md">
                <tr>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">S.No</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Document Type</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Photo</th>
                    <th class="px-4 py-2 border-2 border-gray-400 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <template x-for="(document, index) in {{ $alpine_store }}.documentForm" :key="index">
                    <tr x-show="document">
                        <td class="px-4 py-2 border-2 border-gray-400 w-20" x-text="index + 1"></td>

                        <td class="px-4 py-2 border-2 border-gray-400 w-3/5">
                            <x-select x-model="document.document_type" class="w-full!" :options="$document_types"
                                option-value="value" option-label="label" />
                        </td>

                        <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                            <!-- Upload Box -->
                            <div
                                class="relative w-24 h-24 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer bg-gray-50 hover:bg-gray-100">

                                <!-- Preview -->
                                <template x-if="document.preview">
                                    <img :src="document.preview"
                                        class="absolute inset-0 w-full h-full object-cover rounded-md">
                                </template>

                                <!-- Upload Icon -->
                                <template x-if="!document.preview">
                                    <div class="text-gray-400 text-center text-xs">
                                        <i class="fa-solid fa-image text-lg"></i>
                                        <div>Upload</div>
                                    </div>
                                </template>

                                <!-- File Input -->
                                <input type="file" accept="image/*" wire:ignore
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                    @change.prevent="{{ $alpine_store }}.uploadDocument($event, index)">
                            </div>

                            <!-- Remove Button -->
                            <button x-show="document.preview" type="button"
                                class="text-red-500 text-xs mt-1 hover:underline"
                                @click.prevent="{{ $alpine_store }}.removeDocumentFile(index)">
                                Remove
                            </button>

                        </td>

                        <td class="px-4 py-2 border-2 border-gray-400 w-30">
                            <x-button icon="o-plus" @click.prevent="{{ $alpine_store }}.addDocumentRow()"
                                class="btn-xs btn-success text-white" />
                            <template x-if="index > 0">
                                <x-button icon="o-minus" @click.prevent="{{ $alpine_store }}.removeDocumentRow(index)"
                                    class="btn-xs btn-error text-white" />
                            </template>
                        </td>
                    </tr>

                </template>
            </tbody>
        </table>
    </div>
</div>
