<x-app.auth :title="__('Manage Incentives')">
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
                <div class="px-4 py-5 sm:px-6 md:w-1/4 overflow-hidden">
                    <div class="flex justify-start">
                        <h3 class="text-lg text-gray-900">Settings</h3>
                    </div>
                    <x-nav.settings></x-nav.settings>                                    
                </div>
                
                <div class="px-4 py-5 sm:p-6 md:w-3/4">
                    <x-button :href="route('castle.settings.incentives.create')" color="green">
                        @lang('Create a new Incentive')
                    </x-button>
                    <div class="overflow-y-auto mt-3">
                        <x-table>
                            <x-slot name="header">
                                <x-table.th-tr>
                                    <x-table.th by="number_installs">
                                        @lang('# of Installs')
                                    </x-table.th>
                                    <x-table.th by="incentives">
                                        @lang('Incentive')
                                    </x-table.th>
                                    <x-table.th by="installs_achieved">
                                        @lang('% Achieved (Installs)')
                                    </x-table.th>
                                    <x-table.th by="installs_needed">
                                        @lang('Needed (Installs)')
                                    </x-table.th>
                                    <x-table.th by="kw_achievied">
                                        @lang('% Achieved (kW\'s)')
                                    </x-table.th>
                                    <x-table.th by="kw_needed">
                                        @lang('Needed (kW\'s)')
                                    </x-table.th>
                                    <x-table.th></x-table.th>
                                  </x-table.th-tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($incentives as $incentive)
                                    <x-table.tr :loop="$loop">
                                        <x-table.td>{{ $incentive->number_installs }}</x-table.td>
                                        <x-table.td>{{ $incentive->name }}</x-table.td>
                                        <x-table.td>{{ $incentive->installs_achieved }}</x-table.td>
                                        <x-table.td>{{ $incentive->installs_needed }}</x-table.td>
                                        <x-table.td>{{ $incentive->kw_achieved }}</x-table.td>
                                        <x-table.td>{{ $incentive->kw_needed }}</x-table.td>
                                        <x-table.td class="flex space-x-3">
                                            <x-link :href="route('castle.settings.incentives.edit', $incentive)" class="text-sm">Edit</x-link>
                                            <x-form :route="route('castle.settings.incentives.destroy', $incentive->id)" delete
                                                    x-data="{deleting: false}">
                                            <x-link color="red" class="text-sm" type="button"
                                                    x-show="!deleting"
                                                    x-on:click="$dispatch('confirm', {from: $event.target})"
                                                    x-on:confirmed="deleting = true; $el.submit()"
                                                >Delete</x-link>
                                            <span x-show="deleting" class="text-gray-400">Deleting ...</span>
                                            </x-form>
                                        </x-table.td>
                                    </x-table.tr>
                                @endforeach
                            </x-slot>
                        </x-table>
            
                        <x-confirm
                            :title="__('Delete Incentive')"
                            :description="__('Are you sure you want to delete this incemtive?')"
                        ></x-confirm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>