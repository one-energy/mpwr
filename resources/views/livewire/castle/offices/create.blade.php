<div>
    <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
        <a href="{{ route('castle.offices.index') }}"
           class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            < New Office
        </a>
    </div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <x-form :route="route('castle.offices.store')">
            <div class="px-8"
                 x-data="{
                    selectedRegion: null,
                    token: document.head.querySelector('meta[name=csrf-token]').content,
                    officesManagers: null,
                    regions: null
                }"
                 x-init="() => {
                 $watch('selectedRegion', (region) => {
                    const url = '{{ route('getOfficesManager', ':region') }}'.replace(':region', region);

                    fetch(url, {method: 'get',  headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    }})
                        .then(res => res.json())
                        .then((officeManagerData) => $wire.syncManagers(officeManagerData))
                }),
                fetch('{{ route('getRegions', user()->department_id) }}',{method: 'get',  headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }})
                        .then(res => res.json())
                        .then(regionsData => {
                            regions = regionsData
                            selectedRegion = regionsData[0].id
                        });
                 }">
                <div class="mt-6 items-center grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="md:col-span-6 col-span-2">
                        <x-input label="Office Name" name="name"/>
                    </div>
                    @if(user()->hasAnyRole(['Admin', 'Owner']))
                        <div class="md:col-span-3 col-span-2">
                            <x-select x-model="selectedRegion" label="Region" name="region_id">
                                <template x-if="regions" x-for="region in regions" :key="region.id">
                                    <option :value="region.id" x-text="region.name"></option>
                                </template>
                            </x-select>
                        </div>
                    @else
                        <div class="md:col-span-3 col-span-2">
                            <x-select x-model="selectedRegion" label="Region" name="region_id">
                                <template x-if="regions" x-for="region in regions" :key="region.id">
                                    <option :value="region.id"
                                            x-text="region.departmentName + ' - ' + region.name"></option>
                                </template>
                            </x-select>
                        </div>
                    @endif
                    <div class="md:col-span-3 col-span-2">
                        <x-multiselect
                            class="sm:flex-1"
                            trackBy="id"
                            labeledBy="full_name"
                            label="Office Managers"
                            name="office_manager_ids[]"
                            :options="$managers"
                            wire:key="{{ $this->wireKey }}"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-start border-gray-200 py-5 px-8">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                        Add Office
                    </button>
                </span>
                <span class="ml-3 inline-flex rounded-md shadow-sm">
                    <a href="{{route('castle.offices.index')}}"
                        class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                        Cancel
                    </a>
                </span>
            </div>
        </x-form>
    </div>
</div>