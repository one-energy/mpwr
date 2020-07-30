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
                    <x-button :href="route('castle.users.create')" color="gray">
                        @lang('Create a new User')
                    </x-button>
                    
                    <div class="mt-3">
                        <div class="flex flex-col">
                            <div class="">
                                <div class="align-middle inline-block min-w-full">
                                    <x-table :pagination="$users->links()">
                                        <x-slot name="header">
                                            <tr class="sm:border-gray-200 border-b-2">
                                                <x-table.th by="first_name" :sortedBy="$sortBy" :direction="$sortDirection" class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                    @lang('Name')
                                                </x-table.th>
                                                <x-table.th by="email" :sortedBy="$sortBy" :direction="$sortDirection" class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                    @lang('Email')
                                                </x-table.th>
                                                <x-table.th by="role" :sortedBy="$sortBy" :direction="$sortDirection" class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                    @lang('Role')
                                                </x-table.th>
                                                <x-table.th by="role" :sortedBy="$sortBy" :direction="$sortDirection" class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                    @lang('Office')
                                                </x-table.th>
                                                <x-table.th by="role" :sortedBy="$sortBy" :direction="$sortDirection" class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                                    @lang('Pay')
                                                </x-table.th>
                                                <x-table.th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider"></x-table.th>
                                            </tr>
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($users as $user)
                                                <x-table.tr :loop="$loop">
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">{{ $user->first_name . ' ' . $user->last_name }}</x-table.td>
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">{{ $user->email }}</x-table.td>
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">{{ $user->role }}</x-table.td>
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">{{ $user->office }}</x-table.td>
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">{{ $user->pay }}</x-table.td>
                                                    <x-table.td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
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
