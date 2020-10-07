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
                            @if(user()->role == "Office Manager" || user()->role == "Region Manager")
                                <x-select label="Region" name="region_id" hidden>
                                    @if (old('region_id') == '')
                                        <option selected>None</option>
                                    @endif
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $office->region_id) == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            @else
                                <x-select label="Region" name="region_id">
                                    @if (old('region_id') == '')
                                        <option selected>None</option>
                                    @endif
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $office->region_id) == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            @endif
                        </div>
                        <div class="md:col-span-3 col-span-2">
                            @if(user()->role == "Office Manager")
                                <x-select label="Office Manager" name="office_manager_id" hidden>
                                    @if (old('office_manager_id') == '')
                                        <option selected>None</option>
                                    @endif
                                    @foreach($users as $office_manager)
                                        @if($office_manager->role == 'Office Manager')
                                            <option value="{{ $office_manager->id }}" {{ old('office_manager_id', $office->office_manager_id) == $office_manager->id ? 'selected' : '' }}>
                                                {{ $office_manager->first_name }} {{ $office_manager->last_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </x-select>
                            @else
                                <x-select label="Office Manager" name="office_manager_id">
                                    @if (old('office_manager_id') == '')
                                        <option selected>None</option>
                                    @endif
                                    @foreach($users as $office_manager)
                                        @if($office_manager->role == 'Office Manager')
                                            <option value="{{ $office_manager->id }}" {{ old('office_manager_id', $office->office_manager_id) == $office_manager->id ? 'selected' : '' }}>
                                                {{ $office_manager->first_name }} {{ $office_manager->last_name }}
                                            </option>
                                        @endif
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
        <livewire:castle.manager-members :office="$office"/>
    </div>
</x-app.auth>