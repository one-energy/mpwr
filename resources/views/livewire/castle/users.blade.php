<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="md:flex">
            <div class="px-4 py-5 sm:px-6 md:w-1/3 overflow-hidden">
                <div class="flex justify-start pb-4">
                    <h3 class="text-lg text-gray-900">Users</h3>
                </div>

                <x-filters :keywords="$keywords"></x-filters>
            </div>

            <div class="px-4 py-5 sm:px-6 w-2/3">
                <div>
                    <x-button :href="route('castle.users.create')" color="green">
                        @lang('Create a new User')
                    </x-button>
                    
                    <div class="mt-6">
                        <div class="flex flex-col">
                            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="align-middle inline-block min-w-full overflow-hidden">
                                    <x-table :pagination="$users->links()">
                                        <x-slot name="header">
                                            <tr class="sm:border-gray-200 border-b-2">
                                                <x-table.th-searchable by="first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Name')
                                                </x-table.th>
                                                <x-table.th-searchable by="email" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Email')
                                                </x-table.th>
                                                <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Role')
                                                </x-table.th>
                                                <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Office')
                                                </x-table.th>
                                                <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                                    @lang('Pay')
                                                </x-table.th>
                                                <x-table.th></x-table.th>
                                            </tr>
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($users as $user)
                                                <x-table.tr :loop="$loop" onclick="window.location='{{route('castle.users.show', $user->id)}}';" class="cursor-pointer">
                                                    <x-table.td>{{ $user->first_name . ' ' . $user->last_name }}</x-table.td>
                                                    <x-table.td>{{ $user->email }}</x-table.td>
                                                    <x-table.td>{{ $user->role }}</x-table.td>
                                                    <x-table.td>{{ $user->office }}</x-table.td>
                                                    <x-table.td>{{ $user->pay }}</x-table.td>
                                                    <x-table.td>
                                                        <x-link class="text-sm" :href="route('castle.users.edit', $user->id)">
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
    </div>
</div>