@props([
    'perPages' => [
        ['key' => '1', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('1') : '1'],
        ['key' => '10', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('10') : '10'],
        ['key' => '50', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('50') : '50'],
        ['key' => '100', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('100') : '100'],
        ['key' => '500', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('500') : '500'],
        ['key' => '1000', 'label' => session('locale') == 'np' ? EngToNpNumberConverter('1000') : '1000'],
    ],
])

<div {{ $attributes->class(['flex justify-end']) }} class="flex justify-end">
    {{-- <pre>{{ print_r($perPages, true) }}</pre> --}}
    <div class="flex gap-2 items-center">
        <label>{{ __('Per Page') }}</label>
        <x-select class="w-22!"
            :options="$perPages" option-value="key" option-label="label" wire:model.live.debounce='perPage'
            id="perPage" name="perPage" :key="session('locale')" />
    </div>
</div>
