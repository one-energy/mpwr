<x-app.auth :title="$user->first_name" :header="$user->first_name">
    <div class="bg-white flex flex-col pb-16 rounded-lg shadow px-8 mb-8">
        <div class="my-8">
            <x-link :href="route('castle.users.index')" color="green" class="flex self-center">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('Users')
            </x-link>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center">
            <img class="mb-8 rounded-full self-center sm:mb-0 sm:mr-8 w-32 h-32"
                 src="{{ $user->photo_url }}"
                 alt=""/>
            <div class="tracking-wide">
            <div class="mb-2 flex flex-col sm:flex-row">
                    <div class="font-medium sm:mr-2">Name:</div>
                    {{ $user->first_name . ' ' . $user->last_name }}
                </div>
                <div class="mb-2 flex flex-col sm:flex-row">
                    <div class="font-medium sm:mr-2">Email Address:</div>
                    <a :href="`mailto:{{ $user->email }}`"
                       class="text-green-base underline">{{ $user->email }}</a>
                </div>
                <div class="flex flex-col sm:flex-row">
                    <div class="font-medium sm:mr-2">Joined:</div>
                    <div>{{ $user->created_at->format('F dS, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white px-4 py-5 border-b border-gray-300 sm:px-6 rounded-t-lg shadow">
        <div class="text-lg font-medium">
            Regions
        </div>
    </div>
    <div class="bg-white shadow overflow-hidden rounded-b-lg">
        @foreach($user->regions as $region)
            <div class="border-b border-gray-200 last:border-none hover:bg-gray-50">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-row items-center">
                            <div class="font-medium text-green-base">
                                {{ $region->name }}
                            </div>
                        </div>
                        <div class="ml-2 flex-shrink-0 flex">
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full {{ $region->pivot->role === 'owner' ? 'bg-green-100 text-green-base' : 'bg-gray-200 text-gray-600' }}">
                                {{ Str::ucfirst($region->pivot->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <div class="mr-6 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" fill="currentColor"
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
                                Joined: {{ $region->pivot->created_at->format('F dS, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app.auth>
