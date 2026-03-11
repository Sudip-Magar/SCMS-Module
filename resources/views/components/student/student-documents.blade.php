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
            <template x-for="(document, index) in $wire.documentForm" :key="index">
                <tr x-show="document">
                    <td class="px-4 py-2 border-2 border-gray-400 w-20" x-text="index + 1"></td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-3/5">
                        <x-select x-model="document.document_type" class="w-full!" :options="$document_types"
                                  option-value="value" option-label="label"/>
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-1/5">
                        <!-- Upload Box -->
                        <div
                            class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center cursor-pointer bg-gray-50 hover:bg-gray-100 relative">

                            <!-- Preview Image -->
                            <template x-if="document.preview">
                                <div class="relative w-full h-full">
                                    <img :src="document.preview" class="w-full h-full object-cover rounded-md">
                                    <button type="button"
                                            @click.prevent="{{ $alpine_store }}.removeDocumentFile(index)"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md hover:bg-red-600 z-100 cursor-pointer">
                                        <i class="fa-solid fa-x"></i>
                                    </button>
                                </div>
                            </template>

                            <!-- Upload Icon -->
                            <template x-if="!document.preview">
                                <div class="flex flex-col items-center justify-center text-gray-400 text-xs">
                                    <i class="fa-solid fa-image text-lg mb-1"></i>
                                    <span>Upload</span>
                                </div>
                            </template>

                            <!-- File Input -->
                            <input type="file" accept="image/*" wire:ignore
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change.prevent="{{ $alpine_store }}.uploadDocument($event, index)">
                        </div>
                    </td>

                    <td class="px-4 py-2 border-2 border-gray-400 w-30">
                        <x-button icon="o-plus" @click.prevent="{{ $alpine_store }}.addDocumentRow()"
                                  class="btn-xs btn-success text-white"/>
                        <template x-if="index > 0">
                            <x-button icon="o-minus" @click.prevent="{{ $alpine_store }}.removeDocumentRow(index)"
                                      class="btn-xs btn-error text-white"/>
                        </template>
                    </td>
                </tr>

            </template>
            </tbody>
        </table>
    </div>
</div>
