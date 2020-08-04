<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <x-form :route="route('profile.update')" put>
        <x-card>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            {{ __('Profile') }}
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            {{ __('Main information about yourself.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 sm:px-0">
                        <x-input :label="__('First Name')" name="first_name" autofocus :value="user()->first_name"/>
                        <x-input :label="__('Last Name')" name="last_name" autofocus :value="user()->last_name"/>

                        <x-input :label="__('Email')" name="email" class="mt-6" :value="user()->email"/>

                        <div class="mt-6">
                            <label for="photo" class="block text-sm leading-5 font-medium text-gray-700">
                                Photo
                            </label>
                            <div class="mt-2 flex items-center">
                                <img class="rounded-full w-16"
                                        src="{{ user()->photo_url }}"
                                        alt=""/>
                                <span class="ml-5 rounded-md shadow-sm">
                                    <button type="button"
                                            class="py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                        Change
                                    </button>
                                </span>
                            </div>
                        </div>
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
</div>