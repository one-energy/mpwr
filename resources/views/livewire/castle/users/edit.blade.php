<div>
    <div x-data="{openModal: false}">
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('castle.users.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('User Info')
            </x-link>
        </div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.users.update', $user->id)" put>
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="First Name" name="first_name" wire:model="user.first_name"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Last Name" name="last_name" wire:model="user.last_name"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Email" name="email" wire:model="user.email"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select wire:change="changeRole($event.target.value)" wire:model="user.role" label="Role" name="role">
                                    @foreach ($roles as $role)
                                        <option value="{{$role['name']}}" > {{$role['title']}}</option>
                                    @endforeach
                            </x-select>
                        </div>

                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 hidden">
                                <x-select wire:model="user.department_id" label="Department" name="department_id">
                                    @foreach($departments as $department)
                                        <option value="{{$user->department_id}}">{{$department->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-select wire:change="changeDepartment($event.target.value)" wire:model="user.department_id" label="Department" name="department_id">
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        @endif

                        <div class="md:col-span-3 col-span-2">
                            <x-select wire:model="user.office_id" label="Office" name="office_id">
                                <option value="">
                                    None
                                </option>
                                @foreach($offices as $office)
                                    <option value="{{$office->id}}">{{$office->name}}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input-currency wire:model="user.pay" label="Sale Rate ($/W)" name="pay" />
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-2 flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Update User
                        </button>
                    </span>

                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.users.request-reset-password', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Reset Password
                        </a>
                    </span>

                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="#"
                            x-on:click="openModal = true"
                            class="inline-flex justify-center py-2 px-4 border-2 border-red-500 text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out"
                            onclick="event.preventDefault();
                                    document.getElementById('delete-form').submit();"
                        >
                            Delete User
                        </a>
                    </span>

                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.users.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>

            </x-form>
        </div>

        <div x-cloak x-show="openModal" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <x-form :route="route('castle.users.destroy', $user->id)" delete>
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Delete <span class="font-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </h3>
                            <div class="mt-2">
                            <p class="text-sm leading-5 text-gray-500">
                                Are you sure you want to delete this user?
                            </p>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button x-on:click="openModal = false" type="submit" class="inline-flex justify-center py-2 px-4 border-2 border-red-500 rounded-md text-sm leading-5 font-medium text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out">
                                {{ __('Delete') }}
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button x-on:click="openModal = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancel
                            </button>
                        </span>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
</div>
