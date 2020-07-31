<x-app.guest :title="__( 'Registration' )">
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                {{ __('Finish your registration') }}
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <x-form :route="route('register.with-invitation', $invitation)">

                    <x-input class="mt-6" :label="__('First Name')"
                             name="first_name"></x-input>

                    <x-input class="mt-6" :label="__('Last Name')"
                             name="last_name"></x-input>        

                    <x-input class="mt-6" :label="__('E-mail Address')" name="email" :value="$email" disabled></x-input>

                    <x-input class="mt-6" :label="__('E-mail Address Confirmation')"
                             name="email_confirmation"></x-input>

                    <x-input class="mt-6" type="password" :label="__('Password')" name="password"></x-input>

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <x-button class="w-full flex" type="submit" color="green">
                                {{ __('Register') }}
                            </x-button>
                        </span>
                    </div>
                </x-form>
            </x-card>
        </div>
    </div>
</x-app.guest>
