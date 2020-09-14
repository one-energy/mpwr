<x-app.auth :title="__('Edit Office')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.offices.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Office
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.offices.update', $office)" put>
                @csrf
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-6 col-span-2">
                            <x-input label="Office Name" name="name" value="{{ $office->name }}"></x-input>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select label="Region" name="region_id">
                                @if (old('region_id') == '')
                                    <option selected></option>
                                @endif
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id', $office->region_id) == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="md:col-span-3 col-span-2">
                            <x-select label="Office Manager" name="office_manager_id">
                                @if (old('office_manager_id') == '')
                                    <option selected></option>
                                @endif
                                @foreach($users as $office_manager)
                                    <option value="{{ $office_manager->id }}" {{ old('office_manager_id', $office->office_manager_id) == $office_manager->id ? 'selected' : '' }}>
                                        {{ $office_manager->first_name }} {{ $office_manager->last_name }}
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        </div>
                    </div>
                    <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-start">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Update
                            </button>
                        </span>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.offices.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Cancel
                            </a>
                        </span>
                    </div>
                </div>
            </x-form>
        </div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">        
            <h3 class="text-lg text-gray-900">Manage Offices</h3>
            <div class="grid grid-cols-2 gap-4 mt-6 max-w-4xl mx-auto px-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium leading-5 text-gray-700" for="users_list">Users</label>
                    <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80 overflow-y-auto" id="users_list">
                        @foreach($users as $user)
                            @if($user->office_id != $office->id)
                                <div class="hover:bg-gray-100 h-8 p-1">
                                    {{$user->first_name . ' ' . $user->last_name}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium leading-5 text-gray-700" for="members_list">{{$office->name}} Members</label>
                    <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80" id="members_list">
                        @foreach($users as $user)
                            {{$user}}
                            @if($user->office_id == $office->id)
                                <div class="hover:bg-gray-100 h-8 p-1">
                                    {{$user->first_name . ' ' . $user->last_name}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>