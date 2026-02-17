<div class="h-screen w-full flex items-center justify-center">
    <div class="w-1/3   bg-white p-8 rounded-lg shadow">
        <div class="border-b-2 border-emerald-600 pb-3">
            <h2 class="text-3xl font-bold text-center">Login</h2>
            <p class="text-center mt-1">Nepalese Internation College</p>
        </div>

        <x-form no-separator wire:submit.prevent="login" class="mt-6">
            <x-input label="Email" icon="o-envelope" placeholder="The e-mail" wire:model='email' />
            <x-password label="Password" icon="o-lock-closed" wire:model="password" right />

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Confirm" type="submit" class="btn-primary" spinner="login"/>
            </x-slot:actions>
        </x-form>
    </div>

</div>
