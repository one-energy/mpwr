<x-app.auth :title="__('Invite Castle Master')" :header="__('Invite Castle Master')">
    @if (session('message'))
        <x-alert class="mb-4">
            {!!  session('message') !!}
        </x-alert>
    @endif

    <x-form :route="route('castle.masters.invite')">
        <x-card>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            {{ __('Invite') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __('Invite someone to gain access to the castle.') }}
                        </p>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __('This person will have the same access and information that you do.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 sm:px-0">
                        <x-input :label="__('Email')" name="email"/>
                    </div>
                    <x-slot name="footer">
                        <x-link :href="route('castle.masters.index')" color="gray" class="text-sm mr-4">Cancel</x-link>
                        <x-button type="submit" color="green">
                            {{ __('Invite') }}
                        </x-button>
                    </x-slot>
                </div>
            </div>
        </x-card>
    </x-form>

    <livewire:castle.masters-invitations/>
</x-app.auth>
