<x-app.auth :title="__('Reset User\'s Password')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('castle.users.show', $user->id)" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Edit User Info')
            </x-link>
        </div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.users.reset-password', $user->id)" put>
                <div class="mt-6">
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4">
                        <div class="col-span-1">
                            <x-input :label="__('New Password')" name="new_password" type="password"/>
                        </div>
                        <div class="col-span-1">
                            <x-input :label="__('Password Confirmation')" name="new_password_confirmation" type="password"/>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-2 flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Reset Password
                        </button>
                    </span>

                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.users.show', $user->id)}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
            </x-form>
        </div>
    </div>
</x-app.auth>
