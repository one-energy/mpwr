<x-app.auth :title="__('Permission')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between">
                    <div class="flex justify-start">
                        <x-link :href="route('castle.permission.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                            <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Permission List')
                        </x-link>
                    </div>
                    <div class="flex justify-end">
                        <label class="text-sm text-gray-600">User:</label><label class="text-gray-600 font-semibold ml-1">{{ $user->first_name }} {{ $user->last_name }}</label>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full overflow-hidden">
                            <x-form :route="route('castle.permission.update', $user->id)" put>
                            <x-table>
                                <x-slot name="header">
                                    <x-table.th-tr>
                                        <x-table.th by="assign">
                                            @lang('Assign')
                                        </x-table.th>
                                        <x-table.th by="role_name">
                                            @lang('Role Name')
                                        </x-table.th>
                                        <x-table.th by="description">
                                            @lang('Description')
                                        </x-table.th>
                                    </x-table.th-tr>
                                </x-slot>
                                <x-slot name="body">
                                    @foreach($roles as $role)
                                        <x-table.tr :loop="$loop">
                                            <x-table.td><x-radio label="" name="role" value="{{ $role['name'] }}" :checked="old('role', $user->role)"></x-radio></x-table.td>
                                            <x-table.td>{{ $role['name'] }}</x-table.td>
                                            <x-table.td>{{ $role['description'] }}</x-table.td>
                                        </x-table.tr>
                                    @endforeach
                                </x-slot>
                            </x-table>
                            <div class="mt-8 pt-2 flex md:justify-end">
                                <span class="inline-flex rounded-md shadow-sm">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                        Save
                                    </button>
                                </span>
                            </div>
                            </x-form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>
