<div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg text-gray-900">Manage Regions</h3>
                    </div>
                    <div>
                    @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                        <x-button :href="route('castle.regions.create')" color="green">
                            @lang('Create')
                        </x-button>
                    @endif
                    </div>
            </div>

            <x-search :search="$search"/>

            <div class="mt-6">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full overflow-hidden">
                        <x-table :pagination="$regions->links()">
                            <x-slot name="header">
                                <x-table.th-tr>
                                    @if(user()->role != "Region Manager" && user()->role != "Department Manager")
                                        <x-table.th-searchable by="regions.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Department')
                                        </x-table.th>
                                    @endif
                                    <x-table.th>
                                        @lang('Region')
                                    </x-table.th>
                                    <x-table.th>
                                        @lang('Regional Manager')
                                    </x-table.th>
                                    <x-table.th></x-table.th>
                                    <x-table.th></x-table.th>
                                </x-table.th-tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($regions as $region)
                                    <x-table.tr :loop="$loop">
                                        @if(user()->role != "Region Manager" && user()->role != "Department Manager")
                                            <x-table.td>{{ $region->department->name }}</x-table.td>
                                        @endif
                                        <x-table.td>{{ $region->name }}</x-table.td>
                                        @if($region->regionManger)
                                            <x-table.td>{{ $region->regionManger->first_name }} {{ $region->regionManger->last_name }}</x-table.td>
                                        @else
                                            <x-table.td>Without Manager</x-table.td>
                                        @endif
                                        <x-table.td>
                                            <x-link :href="route('castle.regions.edit', $region)" class="text-sm">Edit</x-link>
                                        </x-table.td>
                                        <x-table.td>
                                            @if(user()->role == "Admin" || user()->role == "Owner" || user()->role == "Department Manager")
                                                <x-form :route="route('castle.regions.destroy', $region->id)" delete
                                                        x-data="{deleting: false}">
                                                    <x-link color="red" class="text-sm" type="button"
                                                            x-show="!deleting"
                                                            wire:click="setDeletingRegion({{$region->id}})"
                                                            x-on:click="$dispatch('confirm', {from: $event.target})">Delete</x-link>
                                                    <span x-cloak x-show="deleting" class="text-gray-400">Deleting ...</span>
                                                </x-form>
                                            @endif
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    </div>

                    <x-modal x-cloak :title="__('Delete Region')"
                            :description="$deleteMessage">

                        <x-form :route="route('castle.regions.destroy', $deletingRegion->id ?? 0)" delete class="w-full p-2">
                            @if($deletingRegion && count($deletingRegion->offices))
                                <x-input class="pb-2" label="Region Name" name="confirmDelete"/>
                            @endif
                            <div class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto justify-end space-x-2">
                                <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                    <button wire:click="setDeletingRegion" type="button" x-on:click="open = false"
                                    class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">

                                        Cancel
                                    </button>
                                </div>
                                <div class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                    <button type="submit"
                                        class=" rounded-md inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 border-2 text-red-500 border-red-500 hover:text-red-600 hover:border-red-600 focus:border-red-500 focus:shadow-outline-red active:bg-red-50">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </x-form>

                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</div>
