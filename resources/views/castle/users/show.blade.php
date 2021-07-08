<x-app.auth :title="$user->first_name">
    <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
        <x-link :href="route('castle.users.index')" color="gray" class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
            <x-svg.chevron-left class="w-6 -ml-2"/> @lang('User Info')
        </x-link>
    </div>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white py-5 border-b border-gray-300 sm:px-6 rounded-t-lg">
            <div class="flex flex-col sm:flex-row sm:items-center">
                <img class="mb-8 rounded-full self-center sm:mb-0 sm:mr-8 w-32 h-32"
                    src="{{ $user->photo_url }}"
                    alt=""/>
                <div class="tracking-wide">
                <div class="mb-2 flex flex-col sm:flex-row">
                        <div class="font-medium sm:mr-2">Name:</div>
                        {{ $user->full_name }}
                    </div>
                    <div class="mb-2 flex flex-col sm:flex-row">
                        <div class="font-medium sm:mr-2">Email Address:</div>
                        <a :href="`mailto:{{ $user->email }}`"
                        class="text-green-base underline">{{ $user->email }}</a>
                    </div>
                    <div class="flex flex-col sm:flex-row mb-2">
                        <div class="font-medium sm:mr-2">Joined:</div>
                        <div>{{ $user->created_at->format('F dS, Y') }}</div>
                    </div>
                    @if ($user->id !== user()->id && !user()->isImpersonated())
                        <div class="flex space-x-2">
                            @canImpersonate()
                                <div class="flex">
                                    <x-button
                                        color="green"
                                        class="cursor-pointer inline text-white px-3 rounded py-1 flex items-center"
                                        href="{{ route('impersonate', $user->id) }}"
                                    >
                                        <x-svg.id-card class="w-5 h-5 text-white fill-current mr-3" />
                                        Impersonate
                                    </x-button>
                                </div>
                            @endCanImpersonate
                            @if(!$user->isVerified() && user()->hasAnyRole(['Admin', 'Owner']))
                                <div class="flex">
                                    <x-form id="resend-verification-form" :route="route('verification.resendInvitationEmail', $user->id)">
                                        <x-button type="submit" 
                                            color="green"
                                            class="border-2 border-green-600 cursor-pointer  px-3 rounded py-1 flex items-center">
                                            <x-svg.at-sign class="w-5 h-5 text-white fill-current mr-3" />
                                            Resend invitation email
                                        </x-button>
                                    </x-form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <livewire:castle.users.user-info-tab :user="$user"/>
    </div>
</x-app.auth>
