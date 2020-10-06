<x-app.auth :title="__('New Office')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.regions.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < New Region
            </a>
        </div>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.regions.store')" post>
                @csrf
                <div>
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Region Name" name="name"></x-input>
                        </div>                    
                        <div class="md:col-span-3 col-span-2">
                            <x-select label="Region Manager" name="region_manager_id">
                                @if (old('region_manager_id') == '')
                                    <option selected>None</option>
                                @endif
                                @foreach($users as $region_manager)
                                    <option value="{{ $region_manager->id }}" {{ old('region_manager_id') == $region_manager->id ? 'selected' : '' }}>
                                        @if(user()->role == "Admin" || user()->role == "Owner")
                                            @if($region_manager->department)
                                                {{$region_manager->department->name}} - {{ $region_manager->first_name }} {{ $region_manager->last_name }}
                                            @else
                                                Without Department - {{ $region_manager->first_name }} {{ $region_manager->last_name }}
                                            @endif
                                        @else
                                            {{ $region_manager->first_name }} {{ $region_manager->last_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="md:col-span-3 col-span-2">
                            @if(user()->role != "Admin" && user()->role != "Owner")
                                <x-select label="Department" name="department_id" hidden>
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
                <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Add Region
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.regions.index')}}" class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>
                </div>
            </x-form>
        </div>
    </div>
</x-app.auth>