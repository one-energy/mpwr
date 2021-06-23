<div>
    <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
        <a href="{{ route('castle.regions.index') }}"
           class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            < New Region
        </a>
    </div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <x-form :route="route('castle.regions.store')">
            <div class="px-8"
                 x-data="{
                    selectedDepartment: null,
                    token: document.head.querySelector('meta[name=csrf-token]').content,
                    departments: null,
                    regionsManager: null
                 }"
                 x-init="() => {
                    $watch('selectedDepartment', department => {
                        const url = '{{ route('getRegionsManager', ':department') }}'.replace(':department', department);
                        fetch(url, {method: 'post',  headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }})
                            .then(res => res.json())
                            .then((regionManagerData) => $wire.syncManagers(regionManagerData))
                    }),
                    fetch('{{ route('getDepartments') }}',{method: 'post',  headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }})
                        .then(res=> res.json())
                        .then(departmentsData => {
                            departments = departmentsData
                            selectedDepartment = '{{user()->department_id ?? 1}}'
                        })
                 }">
                <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="md:col-span-3 col-span-2">
                        <x-input label="Region Name" name="name"/>
                    </div>
                    @if(user()->notHaveRoles(['Admin', 'Owner']))
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
                                <template x-if="departments" x-for="department in departments" :key="department.id">
                                    <option :value="department.id" x-text="department.name"></option>
                                </template>
                            </x-select>
                        </div>
                    @endif
                    <div class="md:col-span-3 col-span-2">
                        <x-multiselect
                            class="sm:flex-1"
                            trackBy="id"
                            labeledBy="full_name"
                            label="Regional Managers"
                            name="region_manager_ids[]"
                            :options="$managers"
                            wire:key="{{ $this->wireKey }}"
                        />
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5 px-8">
                <div class="flex justify-start">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Add Region
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
</div>