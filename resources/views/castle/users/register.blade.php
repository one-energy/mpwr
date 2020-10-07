<x-app.auth :title="__('New User')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('castle.users.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('User Info')
            </x-link>
        </div>

        @if ($message = session('message'))
            <x-alert class="mb-4" :title="__('Success')" :description="$message"></x-alert>
        @endif

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.users.store')">
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('First Name')" name="first_name"/>
                        </div>
                
                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('Last Name')" name="last_name"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('Email')" name="email"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select label="Role" name="role">
                                @if (old('role') == '')
                                    <option selected>None</option>
                                @endif
                                @foreach($roles as $role)
                                <option value="{{ $role['name'] }}" {{ old('role') == $role['name'] ? 'selected' : '' }}>
                                    {{ $role['name']}}
                                </option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <div class="md:col-span-3 col-span-2">
                                <x-select label="Offices" name="office_id">
                                    @if (old('office') == '')
                                        <option selected>None</option>
                                    @endif
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" >
                                            {{ $office['name'] }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input-currency :label="__('Pay')" name="pay"/>
                        </div>
                        
                        <div class="md:col-span-3 col-span-2">
                            <div class="md:col-span-3 col-span-2">
                                @if(user()->role != "Admin" && user()->role != "Owner")
                                    <x-select label="Department" name="department_id" disabledToUser="{{user()->role}}">
                                        @if (old('department') == '')
                                            <option selected>None</option>
                                        @endif
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department', user()->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department['name'] }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                @else
                                    <x-select label="Department" name="department_id">
                                        @if (old('department') == '')
                                            <option selected>None</option>
                                        @endif
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department', user()->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department['name'] }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-2 flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Send Welcome Email
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.users.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
                
            </x-form>
        </div>
    </div>
</x-app.auth>
