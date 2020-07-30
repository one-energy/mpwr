<x-app.auth :title="__('Profile Teste')">
    
    <x-slot name="headerSlot">
        <x-header :text="__('Castle Masters')" class="sm:flex justify-between">
            <x-button :href="route('castle.masters.invite')" color="green" class="mt-4 sm:mt-0">
                @lang('Invite a new Master')
            </x-button>
        </x-header>
    </x-slot>

    <livewire:castle.masters/>
</x-app.auth>
