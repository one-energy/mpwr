<div>

    <x-search :search="$search"/>

    <x-table :pagination="$masters->links()">
        <x-slot name="header">
            <tr>
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
                            <span x-show="revoking" class="text-gray-400">Revoking ...</span>
                            </x-form>
                        @endif
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot>
    </x-table>

    <x-confirm
        :title="__('Revoke Master Access')"
        :description="__('Are you sure you want to revoke this user access to the castle? He will loose all of his powers.')"
     ></x-confirm>
</div>
