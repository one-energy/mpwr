<div>

    <x-search :search="$search">

        <x-select name="teams" class="w-full sm:w-auto" wire:model="team">
            <option value="0" selected>@lang('All teams')</option>
            @foreach ($teams as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </x-select>

    </x-search>

    <x-table :pagination="$users->links()">
        <x-slot name="header">
            <tr>
                <x-table.th-searchable by="first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                    @lang('Name')
                </x-table.th-searchable>
                <x-table.th-searchable by="email" :sortedBy="$sortBy" :direction="$sortDirection">
                    @lang('Email')
                </x-table.th-searchable>
                @if($team)
                    <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                        @lang('Role')
                    </x-table.th-searchable>
                @endif
                <x-table.th></x-table.th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach($users as $user)
                <x-table.tr :loop="$loop">
                    <x-table.td>{{ $user->first_name }}</x-table.td>
                    <x-table.td>{{ $user->email }}</x-table.td>
                    @if($team)
                    <x-table.td>
                        <span class="px-2 py-px tracking-wide text-gray-700 lowercase bg-gray-200 rounded-full">{{ $user->role }}</span>
                    </x-table.td>
                    @endif
                    <x-table.td>
                        <x-link class="text-sm" :href="route('castle.users.show', $user->id)">View</x-link>
                        <x-link class="text-sm">Impersonate</x-link>
                        <x-link class="text-sm">Delete</x-link>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot>
    </x-table>

</div>
