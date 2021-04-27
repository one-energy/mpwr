<x-app.auth :title="__('Edit Office')">
    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('castle.offices.index') }}"
               class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Edit Office
            </a>
        </div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.offices.update', $office)" put>
                <div class="px-8" x-data="{ selectedRegion: null,
                              selectedManager: null,
                              token: document.head.querySelector('meta[name=csrf-token]').content,
                              officesManagers: null,
                              regions: null }"
                     x-init="$watch('selectedRegion', (region) => {
                                const url = '{{ route('getOfficesManager', ':region') }}'.replace(':region', region);
                                fetch(url, {method: 'get',  headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }}).then(res => res.json()).then((officeManagerData) => {
                                    officesManagers = officeManagerData
                                    setTimeout(() => {
                                        selectedManager = {{ $office->office_manager_id ?? 'null' }};
                                    }, 300)
                         })
                     }),

                            fetch('{{ route('getRegions', user()->department_id) }}' ,{method: 'get',  headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }}).then(res=> res.json()).then( (regionsData) => {
                                    regions = regionsData;
                                    selectedRegion = {{ $office->region_id ?? 'null' }};
                         })">

                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input label="Office Name" name="name" value="{{ $office->name }}"/>
                        </div>

                        @if(user()->notHaveRoles(['Admin', 'Owner']))
                            <div
                                class="md:col-span-3 col-span-2 @if(user()->hasAnyRole(['Region Manager', 'Office Manager'])) hidden @endif">
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
                                                x-text="region.department.name + ' - ' + region.name"></option>
                                    </template>
                                </x-select>
                            </div>
                        @endif
                        <div class="md:col-span-3 col-span-2 @if(user()->hasRole('Office manager')) hidden @endif">
                            <span class="block text-sm font-medium leading-5 text-gray-700 mb-1">Managers</span>
                            <div class="border-2 border-gray-200 rounded w-full">
                                <ul class="space-y-2 p-4">
                                    <template x-if="officesManagers" x-for="manager in officesManagers" :key="manager.id">
                                        <li x-text="manager.full_name"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-b border-gray-200 py-2 px-8">
                    <div class="flex justify-start">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Update
                            </button>
                        </span>
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <a href="{{route('castle.offices.index')}}"
                               class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
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
