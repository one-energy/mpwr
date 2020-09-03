<div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg text-gray-900">Manage Masters</h3>
                </div>
                <div class="justify-end">
                    <x-button :href="route('castle.masters.invite')" color="green" class="mt-4 sm:mt-0">
                        @lang('Invite')
                    </x-button>
                </div>
            </div>
            <x-search :search="$search"/>

            <div class="mt-6">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full overflow-hidden">
                        <x-table :pagination="$masters->links()">
                            <x-slot name="header">
                                <tr class="sm:border-gray-200 border-b-2">
                                    <x-table.th-searchable by="first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Name')
                                    </x-table.th-searchable>
                                    <x-table.th-searchable by="email" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Email')
                                    </x-table.th-searchable>
                                    <x-table.th></x-table.th>
                                </tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($masters as $master)
                                    <x-table.tr :loop="$loop">
                                        <x-table.td>{{ $master->first_name . ' ' . $master->last_name }}</x-table.td>
                                        <x-table.td>{{ $master->email }}</x-table.td>
                                        <x-table.td class="flex space-x-3">
                                            <x-link class="text-sm">Edit</x-link>

                                            @if($master->isNot(user()))
                                                <x-form :route="route('castle.masters.revoke', $master)" patch
                                                        x-data="{revoking: false}">
                                                <x-link color="red" class="text-sm" type="button"
                                                        x-show="!revoking"
                                                        x-on:click="$dispatch('confirm', {from: $event.target})"
                                                        x-on:confirmed="revoking = true; $el.submit()"
                                                    >Revoke access</x-link>
                                                <span x-cloak x-show="revoking" class="text-gray-400">Revoking ...</span>
                                                </x-form>
                                            @endif
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    </div>
                </div>
            </div>

            <x-confirm
                x-cloak
                :title="__('Revoke Master Access')"
                :description="__('Are you sure you want to revoke this user access to the castle? He will loose all of his powers.')"
            ></x-confirm>
        </div>
    </div>
</div>
