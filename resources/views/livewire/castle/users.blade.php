<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg text-gray-900">Manage Users</h3>
                </div>
                <div class="justify-end">
                    <x-button :href="route('castle.users.create')" color="green">
                        @lang('Create')
                    </x-button>
                </div>
            </div>

            <x-search :search="$search" />

            <div>
                <input type="checkbox" wire:model="onlyPendingUsers">
                <span>Users without managers</span>
            </div>

            <div class="mt-6">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full overflow-hidden">
                            <x-table :pagination="$users->links()">
                                <x-slot name="header">
                                    <tr class="sm:border-gray-200 border-b-2">
                                        @if(user()->hasRole('Admin'))
                                            <x-table.th by="first_name">
                                                @lang('Department')
                                            </x-table.th>
                                        @endif
                                        <x-table.th-searchable by="first_name" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Name')
                                        </x-table.th-searchable>
                                        <x-table.th-searchable by="email" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Email')
                                        </x-table.th-searchable>
                                        <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Name')
                                        </x-table.th-searchable>
                                        <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Office')
                                        </x-table.th>
                                        <x-table.th-searchable by="role" :sortedBy="$sortBy" :direction="$sortDirection">
                                            @lang('Pay')
                                        </x-table.th-searchable>
                                    </tr>
                                </x-slot>
                                <x-slot name="body">
                                    @foreach($users as $user)
                                        @if($this->canEditUser($user))
                                        <x-table.tr wire:key="user-{{ $user->id }}" :loop="$loop" wire:click="userInfo({{ $user->id }})" class="cursor-pointer">
                                            @if(user()->hasRole('Admin'))
                                                    <x-table.td>{{ $user->department->name ?? 'Without Department' }}</x-table.td>
                                                @endif
                                                <x-table.td>{{ $user->full_name }}</x-table.td>
                                                <x-table.td>{{ $user->email }}</x-table.td>
                                                <x-table.td x-data="{ open: false }" class="relative">
                                                    <div class="flex items-center">
                                                        @if ($this->canSeeOffices($user))
                                                            <div class="hidden md:block">
                                                                <div
                                                                    class="bg-gray-200 rounded shadow-xl w-48 h-auto p-4"
                                                                    style="position: absolute; left: -177px; top: 20px;"
                                                                    x-transition:enter="transition ease-out duration-300"
                                                                    x-transition:enter-start="opacity-0 transform scale-90"
                                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                                    x-transition:leave="transition ease-in duration-300"
                                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                                    x-transition:leave-end="opacity-0 transform scale-90"
                                                                    x-show="open"
                                                                    x-cloak
                                                                >
                                                                    @if ($user->hasRole('Office Manager'))
                                                                        @foreach($user->managedOffices as $office)
                                                                            <p>{{ $office->name }}</p>
                                                                        @endforeach
                                                                    @endif

                                                                    @if ($user->hasAnyRole(['Region Manager', 'Department Manager']))
                                                                        <p>{{ $user->office?->name }}</p>
                                                                    @endif
                                                                </div>
                                                                <x-icon
                                                                    @mouseenter="open = true"
                                                                    @mouseleave="open = false"
                                                                    icon="user"
                                                                    class="w-3.5 h-auto mr-2.5"
                                                                />
                                                            </div>
                                                            <div class="block md:hidden">
                                                                <span
                                                                    @click="$dispatch('confirm', {from: $event.target})"
                                                                    wire:click.stop="openOfficesListModal({{ $user }})"
                                                                >
                                                                   <x-icon icon="user" class="w-3.5 h-auto mr-2.5" />
                                                                </span>
                                                            </div>
                                                        @endif
                                                        {{ $this->userRole($user->role) }}
                                                    </div>
                                                </x-table.td>
                                                <x-table.td>{{ $user->office->name ?? html_entity_decode('&#8212;') }}</x-table.td>
                                                <x-table.td>{{ $user->pay }}</x-table.td>
                                            </x-table.tr>
                                        @else
                                            <x-table.tr wire:key="user-{{ $user->id }}" :loop="$loop" class="cursor-pointer">
                                                @if(user()->hasRole('Admin'))
                                                    <x-table.td>{{ $user->department->name ?? 'Without Department' }}</x-table.td>
                                                @endif
                                                <x-table.td>{{ $user->first_name . ' ' . $user->last_name }}</x-table.td>
                                                <x-table.td>{{ $user->email }}</x-table.td>
                                                <x-table.td>{{ $this->userRole($user->role) }}</x-table.td>
                                                <x-table.td>{{ $user->office->name ?? html_entity_decode('&#8212;') }}</x-table.td>
                                                <x-table.td></x-table.td>
                                            </x-table.tr>
                                        @endif
                                    @endforeach
                                </x-slot>
                            </x-table>
                        </div>
                    </div>

                    <x-modal x-cloak :title="__('Offices List')" :description="$userOffices" :raw="true" :showIcon="false">
                        <div class="flex justify-end">
                            <button
                                x-on:click="open = false"
                                class="rounded-md w-full px-4 py-2 text-base font-medium leading-6 border-2 text-gray-500 border-gray-500 hover:text-gray-600 hover:border-gray-600 focus:border-gray-500 focus:shadow-outline-gray active:bg-gray-50">
                                Close
                            </button>
                        </div>
                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</div>
