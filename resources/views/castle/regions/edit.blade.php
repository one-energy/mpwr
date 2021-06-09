<x-app.auth :title="__('Edit Region')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.regions.index') }}"
               class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Region
            </a>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form class="px-8" :route="route('castle.regions.update', $region)" put>
                <div x-data="{ selectedDepartment: null,
                              selectedRegionManager: null,
                              token: document.head.querySelector('meta[name=csrf-token]').content,
                              departments: null,
                              regionsManager: null }"
                     x-init="$watch('selectedDepartment',
                                     (department) => {
                                    const url = '{{ route('getRegionsManager', ':department') }}'.replace(':department', department);
                                    fetch(url, {method: 'post',  headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    }}).then(res => res.json()).then((regionManagerData) => { regionsManager = regionManagerData }) }),
                            fetch('{{ route('getDepartments') }}',{method: 'post',  headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token
                                    }}).then(res=> res.json()).then( (departmentsData) => {
                                            departments = departmentsData
                                            selectedRegionManager = '{{ $region->regionManager->id ?? 1}}'
                                            selectedDepartment = '{{$region->department_id ?? 1}}'
                                    })">
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Region Name" name="name" value="{{ $region->name }}"/>
                        </div>
                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 hidden">
                                <x-select x-model="selectedDepartment" label="Department" name="department_id">
                                    <template x-if="departments" x-for="department in departments" :key="department.id">
                                        <option :value="department.id" x-text="department.name"></option>
                                    </template>
                                </x-select>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-select x-model="selectedDepartment" label="Department" name="department_id">
                                    <template  x-if="departments" x-for="department in departments" :key="department.id">
                                        <option :value="department.id" x-text="department.name"></option>
                                    </template>
                                </x-select>
                            </div>
                        @endif
                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 @if(user()->role == 'Region Manager') hidden @endif">
                                <x-select x-model="selectedRegionManager" label="Region Manager"
                                          name="region_manager_id">
                                    <template x-if="regionsManager" x-for="manager in regionsManager" :key="manager.id">
                                        <option :value="manager.id"
                                                x-text="manager.first_name + ' ' + manager.last_name"></option>
                                    </template>
                                    
                                    <template x-if="!regionsManager?.length" x-for="manager in regionsManager" :key="manager.id">
                                        <option value="" >No one regional manager was found</option>
                                    </template>
                                </x-select>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-select x-model="selectedRegionManager" label="Region Manager"
                                          name="region_manager_id">
                                    <template x-if="regionsManager" x-for="manager in regionsManager" :key="manager.id">
                                        <option :value="manager.id"
                                                x-text="manager.first_name + ' ' + manager.last_name"></option>
                                    </template>
                                    <template x-if="!regionsManager?.length">
                                        <option value="" >No one regional manager was found</option>
                                    </template>
                                </x-select>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Update
                        </button>
                    </span>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.regions.index')}}"
                           class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                    </div>
                </div>
            </x-form>
        </div>
        <livewire:castle.manage-office :region="$region"/>
    </div>
</x-app.auth>
