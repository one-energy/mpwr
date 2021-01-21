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
                                        @lang('Region Manager')
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
                                        <x-table.td>{{ $region->regionManger->first_name }} {{ $region->regionManger->last_name }}</x-table.td>
                                        <x-table.td>
                                            <x-link :href="route('castle.regions.edit', $region)" class="text-sm">Edit</x-link>
                                        </x-table.td>
                                        <x-table.td>
                                            <x-form :route="route('castle.regions.destroy', $region->id)" delete
                                                    x-data="{deleting: false}">
                                                <x-link color="red" class="text-sm" type="button"
                                                        x-show="!deleting"
                                                        x-on:click="$dispatch('confirm', {from: $event.target})"
                                                        x-on:confirmed="deleting = true; $el.submit()">Delete</x-link>
                                                <span x-cloak x-show="deleting" class="text-gray-400">Deleting ...</span>
                                            </x-form>
                                        </x-table.td>

                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    </div>

                    <x-confirm
                        x-cloak
                        :title="__('Delete Region')"
                        :description="__('Are you sure you want to delete this region?')"
                    ></x-confirm>
                </div>
            </div>
        </div>
    </div>
</div>
