<x-form :route="route('profile.change-password')" put>
    <x-card>
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Security') }}
                    </h3>
                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        {{ __('Update your password. Remember to make it strong.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 sm:px-0">

                    <x-input :label="__('Current Password')" name="current_password" type="pagssword" />
                    <x-input :label="__('New Password')" name="new_password" class="mt-6" type="password" />
                    <x-input :label="__('Confirm Password')" name="new_password_confirmation" class="mt-6" type="password" />

                </div>
                <x-slot name="footer">
                    <x-button type="submit" color="green">
                        {{ __('Save') }}
                    </x-button>
                </x-slot>
            </div>
        </div>
    </x-card>
</x-form>
