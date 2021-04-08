<div>
    <div class="bg-white">
        <nav class="flex justify-center flex-col sm:flex-row">
            <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'userInfo' || $openedTab == 'userEdit') text-green-base border-b-2 font-medium border-green-500 @endif"
                    wire:click="changeTab('userInfo')">
                User Info
            </button>
            <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'orgInfo') text-green-base border-b-2 font-medium border-green-500 @endif"
                    wire:click="changeTab('orgInfo')">
                Org. Assignments
            </button>
            <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'payInfo' || $openedTab == 'payEdit') text-green-base border-b-2 font-medium border-green-500 @endif"
                    wire:click="changeTab('payInfo')">
                Pay Rate
            </button>
        </nav>
    </div>
    <div class="mt-5">
        <div class="@if($openedTab != 'userInfo') hidden @endif">
            <div class="grid grid-cols-2 justify-between gap-4">
                <div>
                    <label class="text-gray-600">First Name</label>
                    <p>{{$user->first_name}}</p>
                </div>
                <div>
                    <span class="text-gray-600">Last Name</span>
                    <p>{{$user->last_name}}</p>
                </div>
                <div>
                    <label class="text-gray-600">Email</label>
                    <p>{{$user->email}}</p>
                </div>
                <div>
                    <span class="text-gray-600">Phone Number</span>
                    <p>{{$user->phone_number ?? '-'}}</p>
                </div>
                <div>
                    <span class="text-gray-600">Role</span>
                    <p>{{$this->userRole($user->role)}}</p>
                </div>
            </div>
            <div class="flex justify-end">
                <x-button wire:click="changeTab('userEdit')" class="place-self-end">Edit</x-button>
            </div>
        </div>
        <div class="@if($openedTab != 'orgInfo') hidden @endif">
            <div class="bg-white overflow-hidden rounded-b-lg">
                @if($teams)
                    @foreach($teams as $team)
                        <div class="border-b border-gray-200 last:border-none hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="font-medium text-green-base">
                                            {{ $team->name }}
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span
                                            class="px-2 inline-flex text-xs font-semibold rounded-full {{ $user->role === 'owner' ? 'bg-green-base text-green-base' : 'bg-gray-200 text-gray-600' }}">
                                            {{ Str::ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <div class="mr-6 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-base" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"/>
                                            </svg>
                                            <span>
                                                Active
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                            <span>
                                                Subscription Plan
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd"/>
                                        </svg>
                                        <span>
                                            Joined: {{ $team->created_at->format('F dS, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="border-b border-gray-200 last:border-none hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-row items-center">
                                    <div class="font-medium text-green-base">
                                        User without office
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex justify-end mt-4">
                <x-button wire:click="changeTab('userEdit')" class="place-self-end">Edit</x-button>
            </div>
        </div>
        <div class="@if($openedTab != 'payInfo') hidden @endif">
            <div class="grid grid-cols-3 justify-between">
                <div class="grid col-span-3 grid-cols-3 gap-x-4 p-4">
                    <div>
                        <label class="text-gray-600">Pay Rate</label>
                        <p>{{$user->pay ? '$ ' . $user->pay : '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Recuited By</span>
                        <p>{{$user->recruitedBy ? $user->recruitedBy?->first_name : '-'}} {{$user->recruitedBy?->last_name}}</p>
                    </div>
                    <div>
                        <label class="text-gray-600">Referral Override</label>
                        <p>{{$user->referral_override ? '$ ' . $user->referral_override : '-'}}</p>
                    </div>
                </div>
                <div class="grid col-span-3 grid-cols-3 gap-x-4 p-4 border border-gray-400 rounded-md bg-gray-100">
                    <div>
                        <span class="text-gray-600">Manager</span>
                        <p>{{$user->officeManager ? $user->officeManager?->first_name : '-'}} {{$user->officeManager?->last_name}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Regional</span>
                        <p>{{$user->regionManager ? $user->regionManager?->first_name : '-'}} {{$user->regionManager?->last_name}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">VP</span>
                        <p>{{$user->departmentManager ? $user->departmentManager?->first_name : '-'}} {{$user->departmentManager?->last_name}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Manager Override</span>
                        <p>{{$user->office_manager_override ? '$ ' . $user->office_manager_override : '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Regional Override</span>
                        <p>{{$user->region_manager_override ? '$ ' . $user->region_manager_override : '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">VP Override</span>
                        <p>{{$user->department_manager_override ? '$ ' . $user->department_manager_override : '-'}}</p>
                    </div>
                </div>
                <div class="grid col-span-3 grid-cols-3 gap-4 p-4">
                    <div>
                        <span class="text-gray-600">Misc. Override 1</span>
                        <p>{{$user->misc_override_one ? '$ ' . $user->misc_override_one : '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Payee</span>
                        <p>{{$user->payee_one ?? '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Note</span>
                        <p>{{$user->note_one ?? '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Misc. Override 2</span>
                        <p>{{$user->misc_override_two ? '$ ' . $user->misc_override_two : '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Payee</span>
                        <p>{{$user->payee_two ?? '-'}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Note</span>
                        <p>{{$user->note_two ?? '-'}}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <x-button wire:click="changeTab('payEdit')" class="place-self-end">Edit</x-button>
            </div>
        </div>
        <div class="@if($openedTab != 'userEdit') hidden @endif">
            <x-form>
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="First Name" name="user.first_name" wire:model="user.first_name" disabled="{{user()->id == $user->id}}"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Last Name" name="user.last_name" wire:model="user.last_name" disabled="{{user()->id == $user->id}}" />
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Email" name="user.email" wire:model="user.email" disabled="{{user()->id == $user->id}}"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input-phone label="Phone Number" name="user.phone_number" wire:model="user.phone_number" disabled="{{user()->id == $user->id}}"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select wire:change="changeRole($event.target.value)" wire:model="user.role" label="Role" name="user.role" disabled="{{user()->id == $user->id}}">
                                    @foreach ($roles as $role)
                                        <option value="{{$role['name']}}" > {{$role['title']}}</option>
                                    @endforeach
                            </x-select>
                        </div>

                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 hidden">
                                <x-select wire:model="user.department_id" label="Department" name="user.department_id" disabled="{{user()->id == $user->id}}">
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
                            <x-select wire:model="user.office_id" label="Office" name="user.office_id" disabled="{{user()->id == $user->id}}">
                                @if($user->role != "Office Manager" && $user->role != "Sales Rep" && $user->role != "Setter")
                                    <option value="">
                                        None
                                    </option>
                                @endif
                                @if(!$offices->count())
                                    <option value="">No offices in department</option>
                                @endif
                                @foreach($offices as $office)
                                    <option value="{{$office->id}}">{{$office->name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-2 flex justify-between">
                    <div>
                        @if(user()->id != $user->id)
                            <span class="ml-3 inline-flex rounded-md shadow-sm">
                                <a href="#"
                                    x-on:click="$dispatch('confirm', {from: $event.target})"
                                    x-on:confirmed=""
                                    class="inline-flex justify-center py-2 px-4 border-2 border-red-500 text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out">
                                    Delete User
                                </a>
                            </span>
                        @endif
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.users.request-reset-password', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-500 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Reset Password
                            </a>
                        </span>
                    </div>
                    <div>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.users.show', $user->id)}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Cancel
                            </a>
                        </span>
                        @if(user()->id != $user->id)
                            <span class="inline-flex rounded-md shadow-sm">
                                <button wire:click="update" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-base hover:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                    Update User
                                </button>
                            </span>
                        @endif
                    </div>
                </div>
            </x-form>
        </div>
        <div class="@if($openedTab != 'payEdit') hidden @endif">
            <div class="grid grid-cols-3 justify-between">
                <x-form class="col-span-3" wire:submit.prevent="saveOverride">
                    <div class="grid col-span-3 grid-cols-3 gap-x-4 p-4">
                        <div>
                            <x-input-currency wire:model="userOverride.pay" name="user.pay" label="Pay"/>
                        </div>
                        <div>
                            <x-select wire:model="userOverride.recruiter_id" name="userOverride.recruiter_id" label="Recuited By">
                                <option value="">None</option>
                                @foreach($departmentUsers as $userOnDepartment)
                                    <option value="{{$userOnDepartment->id}}">{{$userOnDepartment->first_name}} {{$userOnDepartment->last_name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-input-currency wire:model="userOverride.referral_override" name="userOverride.referral_override" label="Referral Override"/>
                        </div>
                    </div>
                    <div class="grid col-span-3 grid-cols-3 gap-x-4 gap-y-1 p-4 border border-gray-400 rounded-md bg-gray-100">
                        <div>
                            <x-select wire:model="userOverride.office_manager_id" name="userOverride.office_manager_id" label="Manager">
                                <option value="">None</option>
                                @foreach($officeManagerUsers as $officeManager)
                                    <option value="{{$officeManager->id}}">{{$officeManager->first_name}} {{$officeManager->last_name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-select wire:model="userOverride.region_manager_id" name="userOverride.region_manager_id" label="Regional Manager">
                                <option value="">None</option>
                                @foreach($regionManagerUsers as $regionManager)
                                    <option value="{{$regionManager->id}}">{{$regionManager->first_name}} {{$regionManager->last_name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-select wire:model="userOverride.department_manager_id" name="department_manager_id" label="VP">
                                <option value="">None</option>
                                @foreach($departmentManagerUsers as $departmentManager)
                                    <option value="{{$departmentManager->id}}">{{$departmentManager->first_name}} {{$departmentManager->last_name}}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div>
                            <x-input-currency wire:model="userOverride.office_manager_override" name="userOverride.office_manager_override" label="Manager Override"/>
                        </div>
                        <div>
                            <x-input-currency wire:model="userOverride.region_manager_override" name="userOverride.region_manager_override" label="Regional Override"/>
                        </div>
                        <div>
                            <x-input-currency wire:model="userOverride.department_manager_override" name="userOverride.department_manager_override" label="VP Override"/>
                        </div>
                    </div>
                    <div class="grid col-span-3 grid-cols-3 gap-4 p-4">
                        <div>
                            <x-input-currency wire:model="userOverride.misc_override_one" name="userOverride.misc_override_one" label="Misc Override 1"/>
                        </div>
                        <div>
                            <x-input wire:model="userOverride.payee_one" name="userOverride.payee_one" label="Payee"></x-input>
                        </div>
                        <div>
                            <x-input wire:model="userOverride.note_one" name="userOverride.note_one" label="Note"></x-input>
                        </div>
                        <div>
                            <x-input-currency wire:model="userOverride.misc_override_two" name="userOverride.misc_override_two" label="Misc Override 2"/>
                        </div>
                        <div>
                            <x-input wire:model="userOverride.payee_two" name="userOverride.payee_two" label="Payee"></x-input>
                        </div>
                        <div>
                            <x-input wire:model="userOverride.note_two" name="userOverride.note_two" label="Note"></x-input>
                        </div>
                    </div>
                    <div class="mt-8 pt-2 flex justify-between">
                        <div>
                            @if(user()->id != $user->id)
                                <span class="ml-3 inline-flex rounded-md shadow-sm">
                                    <a href="#"
                                        x-on:click="$dispatch('confirm', {from: $event.target})"
                                        x-on:confirmed=""
                                        class="inline-flex justify-center py-2 px-4 border-2 border-red-500 text-sm leading-5 font-medium rounded-md text-red-500 hover:text-red-600 hover:border-red-600 focus:outline-none focus:border-red-500 focus:shadow-outline-red active:bg-red-50 transition duration-150 ease-in-out">
                                        Delete User
                                    </a>
                                </span>
                            @endif
                            <span class="ml-3 inline-flex rounded-md shadow-sm">
                                <a href="{{route('castle.users.request-reset-password', $user->id)}}" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-500 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                    Reset Password
                                </a>
                            </span>
                        </div>
                        <div>
                            <span class="ml-3 inline-flex rounded-md shadow-sm">
                                <a href="{{route('castle.users.show', $user->id)}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                    Cancel
                                </a>
                            </span>
                            @if(user()->id != $user->id)
                                <span class="inline-flex rounded-md shadow-sm">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-base hover:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                        Update User
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
    </div>
    <x-modal x-cloak :title="__('Delete user')"
            :description="__('Are you sure you want to delete this user?')">
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
