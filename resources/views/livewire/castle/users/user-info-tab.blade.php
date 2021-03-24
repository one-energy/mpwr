<div>
    <div>
        <div class="bg-white">
            <nav class="flex flex-col sm:flex-row">
                <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'userInfo') text-green-base border-b-2 font-medium border-green-500 @endif"
                        wire:click="changeTab('userInfo')">
                    User Info
                </button>
                <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'orgInfo') text-green-base border-b-2 font-medium border-green-500 @endif"
                        wire:click="changeTab('orgInfo')">
                    Org. Assignments
                </button>
                <button class="text-gray-600 py-4 px-6 block hover:text-green-base focus:outline-none @if($openedTab == 'payInfo') text-green-base border-b-2 font-medium border-green-500 @endif"
                        wire:click="changeTab('payInfo')">
                    Pay Rate
                </button>
            </nav>
        </div>
        <div class="mt-5">
            <div class="@if($openedTab != 'userInfo') hidden @endif">
                <div class="grid grid-cols-2 justify-between gap-4">
                    <div>
                        <label class="text-gray-600">First Name</label>
                        <p>{{$user->first_name}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Last Name</span>
                        <p>{{$user->last_name}}</p>
                    </div>
                    <div>
                        <label class="text-gray-600">Email</label>
                        <p>{{$user->email}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Phone Number</span>
                        <p>{{$user->last_name}}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Role</span>
                        <p>{{$this->userRole($user->role)}}</p>
                    </div>
                </div>
            </div>
            <div class="@if($openedTab != 'orgInfo') hidden @endif">
                <div class="bg-white overflow-hidden rounded-b-lg">
                    @if($teams)
                        @foreach($teams as $team)
                            <div class="border-b border-gray-200 last:border-none hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-row items-center">
                                            <div class="font-medium text-green-base">
                                                {{ $team->name }}
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <span
                                                class="px-2 inline-flex text-xs font-semibold rounded-full {{ $user->role === 'owner' ? 'bg-green-base text-green-base' : 'bg-gray-200 text-gray-600' }}">
                                                {{ Str::ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <div class="mr-6 flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-base" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"/>
                                                </svg>
                                                <span>
                                                    Active
                                                </span>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                    <path
                                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                                <span>
                                                    Subscription Plan
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd"/>
                                            </svg>
                                            <span>
                                                Joined: {{ $team->created_at->format('F dS, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="border-b border-gray-200 last:border-none hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="font-medium text-green-base">
                                            User without office
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="@if($openedTab != 'payInfo') hidden @endif">
                teste
            </div>
        </div>
    </div>
</div>
