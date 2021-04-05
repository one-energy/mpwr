<x-app.auth :title="__('Incentives')">
    <div>
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Incentives</h3>
                </div>

                <div class="mt-6">
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div class="align-middle inline-block min-w-full overflow-hidden">
                                @if($incentives->count())
                                    <x-table>
                                        <x-slot name="header">
                                            <x-table.th-tr>
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
                                            </x-table.th-tr>
                                        </x-slot>
                                        <x-slot name="body">
                                            @foreach($incentives as $index => $incentive)
                                                <x-table.tr :loop="$loop">
                                                    @php ($nextIncentive = $incentives->get(++$index) ?? $incentives->last())
                                                    @php ($lastIncentive = $incentives->last())
                                                    <x-table.td>{{ $incentive->name }}</x-table.td>
                                                    <x-table.td>{{ number_format(($myInstalls / $incentive->installs_needed) * 100, 2) }}
                                                        %
                                                    </x-table.td>
                                                    <x-table.td>{{ $incentive->installs_needed }}</x-table.td>
                                                    <x-table.td>{{ number_format(($myKws / $incentive->kw_needed) * 100, 2) }}
                                                        %
                                                    </x-table.td>
                                                    <x-table.td>
                                                        <div
                                                            class="@if(($myKws >= $incentive->kw_needed && $myKws < $nextIncentive->kw_needed) || ($myKws >= $lastIncentive->kw_needed && $loop->last)) text-green-base font-bold @endif">
                                                            {{ $incentive->kw_needed }}
                                                        </div>
                                                    </x-table.td>
                                                </x-table.tr>
                                            @endforeach
                                        </x-slot>
                                    </x-table>
                                @else
                                    <div class="h-96 ">
                                        <div class="flex justify-center align-middle">
                                            <div class="text-sm text-center text-gray-700">
                                                <x-svg.draw.empty></x-svg.draw.empty>
                                                No data yet.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end items-center mt-3 p-3 text-gray-600">
                            <span class="text-xs">My installs:</span><span
                                class="text-sm font-bold ml-1">{{ $myInstalls }}</span>
                            <span class="text-xs ml-3">My kw's:</span><span
                                class="text-sm font-bold ml-1">{{ $myKws }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>
