<div>
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <h3 class="text-lg text-gray-900">Manage Offices</h3>
                      </div>
                      @if(user()->role != "Office Manager")
                        <div>
                            <x-button :href="route('castle.offices.create')" color="green">
                                @lang('Create')
                            </x-button>
                        </div>
                      @endif
                </div>

                <x-search :search="$search"/>
              
                <div class="mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full overflow-hidden">
                            <x-table :pagination="$offices->links()">
                                <x-slot name="header">
                                    <x-table.th-tr>
                                        <x-table.th-searchable by="offices.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Office')
                                        </x-table.th>
                                        <x-table.th-searchable by="regions.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Region')
                                        </x-table.th>
                                        <x-table.th-searchable by="users.first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Office Manager')
                                        </x-table.th>
                                        <x-table.th></x-table.th>
                                        </x-table.th-tr>
                                </x-slot>
                                <x-slot name="body">
                                    @foreach($offices as $office)
                                        <x-table.tr :loop="$loop">
                                            <x-table.td>{{ $office->name }}</x-table.td>
                                            <x-table.td>{{ $office->region->name }}</x-table.td>
                                            <x-table.td>{{ $office->office_manager->first_name }} {{ $office->office_manager->last_name }}</x-table.td>
                                            <x-table.td class="flex space-x-3">
                                                <x-link :href="route('castle.offices.edit', $office)" class="text-sm">Edit</x-link>
                                                <x-form :route="route('castle.offices.destroy', $office->id)" delete
                                                        x-data="{deleting: false}">
                                                <x-link color="red" class="text-sm" type="button"
                                                        x-show="!deleting"
                                                        x-on:click="$dispatch('confirm', {from: $event.target})"
                                                        x-on:confirmed="deleting = true; $el.submit()"
                                                    >Delete</x-link>
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
                            :title="__('Delete Office')"
                            :description="__('Are you sure you want to delete this office?')"
                        ></x-confirm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>