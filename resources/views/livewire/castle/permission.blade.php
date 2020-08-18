<div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg text-gray-900">Manage Permission</h3>
            
            <div class="mt-6">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full overflow-hidden">
                        <x-table :pagination="$users->links()">
                            <x-slot name="header">
                                <x-table.th-tr>
                                    <x-table.th-searchable by="first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Name')
                                    </x-table.th>
                                    <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                        @lang('Role')
                                    </x-table.th>
                                    <x-table.th></x-table.th>
                                </x-table.th-tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($users as $user)
                                    <x-table.tr :loop="$loop">
                                        <x-table.td>{{ $user->first_name . ' ' . $user->last_name }}</x-table.td>
                                        <x-table.td>{{ $user->role }}</x-table.td>
                                        <x-table.td>
                                            <x-link class="text-sm" :href="route('castle.permission.edit', $user->id)">
                                                Edit
                                            </x-link>
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>