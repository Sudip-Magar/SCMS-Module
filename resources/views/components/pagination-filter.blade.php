@props([
    'perPages' => [
    ['value' => '1', 'label' => '1'], 
    ['value' => '10', 'label' => '10'],
    ['value' => '50', 'label' => '50'],
    ['value' => '100', 'label' => '100'],
    ['value' => '500', 'label' => '500'],
    ['value' => '1000', 'label' => '1000'],
    ],
])
<div class="flex justify-end">
    <div class="flex gap-2 items-center">
        <label>{{ __('Per Page') }}</label>
        <x-select class="w-22!" x-bind:class="$store.darkmode.toggle ? 'bg-gray-900 text-white' : 'bg-white text-black'"
            :options="$perPages" option-value='value' option-label="label" wire:model.live.debounce='perPage' />
    </div>
</div>
