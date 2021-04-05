<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg text-gray-900">Manage Compensations</h3>
                </div>
                <div>
                    <x-button :href="route('castle.rates.create')" color="green">
                        @lang('Create')
                    </x-button>
                </div>
            </div>
            @if(count($rates) > 0)
                <x-search :search="$search" />

                <div class="mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div class="align-middle inline-block min-w-full overflow-hidden">
                                <x-table :pagination="$rates->links()">
                                    <x-slot name="header">
                                        <x-table.th-tr>
                                            @if(user()->role == "Admin" || user()->role == "Owner")
                                                <x-table.th-searchable by="rates.name" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Department')
                                                </x-table.th>
                                            @endif
                                            <x-table.th>
                                                @lang('Title')
                                            </x-table.th>
                                            <x-table.th>
                                                @lang('Rate')
                                            </x-table.th>
                                            <x-table.th></x-table.th>
                                            <x-table.th></x-table.th>
                                        </x-table.th-tr>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach($rates as $rate)
                                        <x-table.tr :loop="$loop">
                                            @if(user()->role == "Admin" || user()->role == "Owner")
                                                <x-table.td>{{ $rate->department->name}}</x-table.td>
                                            @endif
                                            <x-table.td>{{ $rate->name }}</x-table.td>
                                            <x-table.td>{{ $rate->rate }}</x-table.td>
                                            <x-table.td>
                                                <x-link :href="route('castle.rates.edit', $rate)" class="text-sm">Edit</x-link>
                                            </x-table.td>
                                            <x-table.td>
                                                <x-form :route="route('castle.rates.destroy', $rate->id)" delete x-data="{deleting: false}">
                                                    <x-link color="red" class="text-sm" type="button" x-show="!deleting" x-on:click="$dispatch('confirm', {from: $event.target})" x-on:confirmed="deleting = true; $el.submit()">Delete</x-link>
                                                    <span x-cloak x-show="deleting" class="text-gray-400">Deleting ...</span>
                                                </x-form>
                                            </x-table.td>
                                        </x-table.tr>
                                        @endforeach
                                    </x-slot>
                                </x-table>
                            </div>
                        </div>

                        <x-confirm x-cloak :title="__('Delete rate')" :description="__('Are you sure you want to delete this rate?')"></x-confirm>
                    </div>
                </div>
            @else
                <div class="h-96 ">
                    <div class="flex justify-center align-middle">
                        <div class="text-sm text-center text-gray-700">
                            <x-svg.draw.empty></x-svg.draw.empty>
                            No data yet.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
