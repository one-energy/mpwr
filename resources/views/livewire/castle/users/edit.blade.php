<div>
    <div x-data="{openModal: false}">
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('castle.users.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('User Info')
            </x-link>
        </div>

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.users.update', $user->id)" put>
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="First Name" name="first_name" wire:model="user.first_name" disabled="{{user()->id == $user->id}}"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Last Name" name="last_name" wire:model="user.last_name" disabled="{{user()->id == $user->id}}" />
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Email" name="email" wire:model="user.email" disabled="{{user()->id == $user->id}}"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select wire:change="changeRole($event.target.value)" wire:model="user.role" label="Role" name="role" disabled="{{user()->id == $user->id}}">
                                    @foreach ($roles as $role)
                                        <option value="{{$role['name']}}" > {{$role['title']}}</option>
                                    @endforeach
                            </x-select>
                        </div>

                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 hidden">
                                <x-select wire:model="user.department_id" label="Department" name="department_id" disabled="{{user()->id == $user->id}}">
                                    @foreach($departments as $department)
                                        <option value="{{$user->department_id}}">{{$department->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-select wire:change="changeDepartment($event.target.value)" wire:model="user.department_id" label="Department" name="department_id" disabled="{{user()->id == $user->id}}">
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        @endif

                        <div class="md:col-span-3 col-span-2">
                            <x-select wire:model="user.office_id" label="Office" name="office_id" disabled="{{user()->id == $user->id}}">
                                @if($user->role != "Office Manager" && $user->role != "Sales Rep" && $user->role != "Setter")
                                    <option value="">
                                        None
                                    </option>
                                @endif
                                @if(count($offices) == 0)
                                    <option value="">No offices in department</option>
                                @endif
                                @foreach($offices as $office)
                                    <option value="{{$office->id}}">{{$office->name}}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input-currency wire:model="user.pay" label="Pay Rate ($/W)" name="pay" disabled="{{user()->id == $user->id}}"/>
                        </div>
                    </div>
                </div>

                    <div class="mt-8 pt-2 flex justify-end">

                        @if(user()->id != $user->id)
                            <span class="inline-flex rounded-md shadow-sm">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                    Update User
                                </button>
                            </span>
                        @endif
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.users.request-reset-password', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Reset Password
                            </a>
                        </span>


                        @if(user()->id != $user->id)
                            <span class="ml-3 inline-flex rounded-md shadow-sm">
                                <a href="#"
                                    x-on:click="$dispatch('confirm', {from: $event.target})"
                                    x-on:confirmed=""
                                    class="inline-flex justify-center py-2 px-4 border-2 border-red-500 text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out"

                                >
                                    Delete User
                                </a>
                            </span>
                        @endif
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.users.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Cancel
                            </a>
                        </span>
                    </div>

            </x-form>
        </div>

        <x-modal
            x-cloak
            :title="__('Delete user')"
            :description="__('Are you sure you want to delete this user?')"
        >
            <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                <x-form :route="route('castle.users.destroy', $user->id)" delete>

                    <button type="submit"
                            class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
                        Confirm
                    </button>
                </x-form>
            </div>
            <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button type="button"
                        x-on:click="open = false"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green sm:text-sm sm:leading-5">
                    Cancel
                </button>
            </div>
        </x-modal>
    </div>
</div>
