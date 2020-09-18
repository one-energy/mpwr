<div>
    <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">        
        <h3 class="text-lg text-gray-900">Manage Members</h3>
        <div class="mt-6 max-w-4xl mx-auto px-6">
            <x-search :search="$search" :perPage="false"/>
        </div>
        <div class="grid grid-cols-2 gap-4 max-w-4xl mx-auto px-6">
            <div class="col-span-1">
                <div class="inline-flex grid-cols-6 gap-4 h-8">
                    <div class="col-span-1 py-2">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="users_list">Users</label>
                    </div>
                    <div class="col-span-5">
                        <x-svg.spinner 
                            color="#9fa6b2" 
                            class="relative hidden top-2 w-6" 
                            wire:loading.class.remove="hidden" wire:target="addUserToOffice">
                        </x-svg.spinner>
                    </div>
                </div>
                <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80 overflow-y-auto cursor-pointer" id="users_list">
                    @foreach($users as $user)
                        @if($user->office_id !== $office->id)
                            <div class="hover:bg-gray-100 h-8 p-1 grid grid-cols-6" wire:click="addUserToOffice({{$user}})">
                                <div class="text-right col-span-5">
                                    {{$user->first_name . ' ' . $user->last_name}}
                                </div>
                                <div class="float-right col-span-1">
                                    <x-svg.chevron-right class="float-right w-7 text-gray-500"/>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-span-1">
                <div class="inline-flex grid-cols-6 gap-4 h-8">
                    <div class="col-span-1 py-2">
                        <label class="block text-sm font-medium leading-5 text-gray-700" for="members_list">{{$office->name}} Members</label>
                    </div>
                    <div class="col-span-5">
                        <x-svg.spinner 
                            color="#9fa6b2" 
                            class="relative hidden top-2 w-6" 
                            wire:loading.class.remove="hidden" wire:target="removeUserOffice">
                        </x-svg.spinner>
                    </div>
                </div>
                <div class="border-gray-200 border-2 m-1 p-2 rounded-lg h-80 overflow-y-auto cursor-pointer" id="members_list">
                    @foreach($users as $user)
                        @if($user->office_id === $office->id)
                            <div class="hover:bg-gray-100 h-8 p-1 grid grid-cols-6" wire:click="removeUserOffice({{$user}})">
                                <div class="col-span-1">
                                    <x-svg.chevron-left class="w-7 text-gray-500"/>
                                </div>
                                <div class="col-span-5">
                                    {{$user->first_name . ' ' . $user->last_name}}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
