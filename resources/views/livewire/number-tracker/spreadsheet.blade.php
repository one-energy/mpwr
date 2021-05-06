<div>
    @push('styles')
        <style>
            thead th:first-child {
                left: 0;
                z-index: 1;
            }

            tbody td:first-child {
                position: -webkit-sticky;
                position: sticky;
                left: 0;
                background: #FFF;
            }

            thead th:first-child,
            thead th:last-child {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                background-color: #FFF;
            }

            thead th:last-child {
                right: 0;
                z-index: 1;
            }

            tbody td:last-child {
                position: -webkit-sticky;
                position: sticky;
                right: 0;
                background: #FFF;
            }
        </style>
    @endpush

    <section class="flex flex-col md:flex-row justify-between">
        <div class="max-w-8xl pt-5 sm:px-6 lg:px-8">
            <a href="{{ route('number-tracking.index') }}"
               class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Back to Tracker Overview
            </a>
        </div>

        @if ($this->isAdmin)
            <section class="flex my-6 md:justify-end md:mb-8 md:mt-5">
                <x-select wire:model="selectedOffice" class="w-full md:w-auto" name="offices" label="Offices">
                    @foreach($this->offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </x-select>
            </section>
        @endif
    </section>
    @foreach($this->periodsLabel as $key => $label)
        <section class="mb-10">
            <h3 class="font-bold text-center mb-3">{{ $label }}</h3>
            <div class="overflow-x-auto">
                <x-table id="table">
                    <x-slot name="header">
                        <x-table.th-tr>
                            <x-table.th class="whitespace-no-wrap sticky">
                                @lang('Office Members')
                            </x-table.th>
                            @foreach($this->weeklyLabels[$key] as $label)
                                <x-table.th class="whitespace-no-wrap">
                                    <section class="flex flex-col items-center">
                                        <span class="font-bold">@lang($label)</span>
                                        <div class="w-full flex flex-row items-center justify-between space-x-3">
                                            @foreach($this->indicators as $indicator)
                                                <div
                                                    class="cursor-default"
                                                    x-data=""
                                                    @mouseenter="$dispatch('open-popover', {ref: '{{ $label . $loop->index }}'})"
                                                    @mouseleave="$dispatch('close-popover', {ref: '{{ $label . $loop->index }}'})"
                                                >
                                                    {{ $indicator['label'] }}
                                                    <x-popover
                                                        ref="{{ $label . $loop->index }}">{{ $indicator['description'] }}</x-popover>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                </x-table.th>
                            @endforeach
                            <x-table.th class="whitespace-no-wrap">
                                <section class="flex flex-col items-center">
                                    <span class="font-bold">@lang('Weekly Totals')</span>
                                    <div class="w-full flex flex-row items-center justify-between space-x-3">
                                        <span class="text-center w-6">
                                            HW
                                        </span>
                                        <span class="text-center w-6">
                                            D
                                        </span>
                                        <span class="text-center w-6">
                                            HK
                                        </span>
                                        <span class="text-center w-6">
                                            S
                                        </span>
                                        <span class="text-center w-6">
                                            SA
                                        </span>
                                        <span class="text-center w-6">
                                            SC
                                        </span>
                                        <span class="text-center w-6">
                                            CS
                                        </span>
                                        <span class="text-center w-6">
                                            C
                                        </span>
                                    </div>
                                </section>
                            </x-table.th>
                        </x-table.th-tr>
                    </x-slot>

                    <x-slot name="body">
                        @foreach($this->users as $user)
                            <x-table.tr class="relative">
                                <x-table.td>{{ $user->full_name }}</x-table.td>

                                {{-- Weekly Columns --}}
                                @foreach($this->weeklyLabels[$key] as $label)
                                    @if (isset($user->dailyNumbers[$label]))
                                        <x-table.td>
                                            <section class="w-full flex flex-row items-center justify-between">
                                                <span class="text-center w-6">
                                                    {{ $user->dailyNumbers[$label][0]->hours }}
                                                </span>
                                                <span class="text-center w-6">
                                                    {{ $user->dailyNumbers[$label][0]->doors }}
                                                </span>
                                                <span class="text-center w-6">
                                                    HK
                                                </span>
                                                <span class="text-center w-6">
                                                    {{ $user->dailyNumbers[$label][0]->sets }}
                                                </span>
                                                <span class="text-center w-6">
                                                    SA
                                                </span>
                                                <span class="text-center w-6">
                                                    {{ $user->dailyNumbers[$label][0]->set_closes }}
                                                </span>
                                                <span class="text-center w-6">
                                                    CS
                                                </span>
                                                <span class="text-center w-6">
                                                    {{ $user->dailyNumbers[$label][0]->closes }}
                                                </span>
                                            </section>
                                        </x-table.td>
                                    @else
                                        <x-table.td></x-table.td>
                                    @endif
                                @endforeach

                                {{-- Weekly Totals Column --}}
                                <x-table.td>
                                    <section class="w-full flex flex-row items-center justify-between">
                                        <span class="text-center w-6">
                                            {{ $this->sumOf('hours', $user, $this->weeklyPeriods[$key]) }}
                                        </span>
                                        <span class="text-center w-6">
                                            {{ $this->sumOf('doors', $user, $this->weeklyPeriods[$key]) }}
                                        </span>
                                        <span class="text-center w-6">
                                            HK
                                        </span>
                                        <span class="text-center w-6">
                                            {{ $this->sumOf('sets', $user, $this->weeklyPeriods[$key]) }}
                                        </span>
                                        <span class="text-center w-6">
                                            SA
                                        </span>
                                        <span class="text-center w-6">
                                            {{ $this->sumOf('set_closes', $user, $this->weeklyPeriods[$key]) }}
                                        </span>
                                        <span class="text-center w-6">
                                            CS
                                        </span>
                                        <span class="text-center w-6">
                                            {{ $this->sumOf('closes', $user, $this->weeklyPeriods[$key]) }}
                                        </span>
                                    </section>
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                        <x-table.tr class="relative">
                            <x-table.td>
                                <span class="font-bold">Total</span>
                            </x-table.td>
                            @foreach($this->weeklyLabels[$key] as $label)
                                @if ($this->totals[$key][$label])
                                    <x-table.td>
                                        <section class="w-full space-x-2 flex flex-row items-center justify-between">
                                            <span class="text-center w-6">
                                                {{ $this->totals[$key][$label]['hours'] }}
                                            </span>
                                            <span class="text-center w-6">{{ $this->totals[$key][$label]['doors'] }}
                                            </span>
                                            <span class="text-center w-6">
                                                2
                                            </span>
                                            <span class="text-center w-6">
                                                HK
                                            </span>
                                            <span class="text-center w-6">
                                                3
                                            </span>
                                            <span class="text-center w-6">
                                                SA
                                            </span>
                                            <span class="text-center w-6">
                                                4
                                            </span>
                                            <span class="text-center w-6">
                                                CS
                                            </span>
                                            <span class="text-center w-6">
                                                5
                                            </span>
                                        </section>
                                    </x-table.td>
                                @else
                                    <x-table.td>
                                        <section class="w-full space-x-2 flex flex-row items-center justify-between">
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                            <span class="text-center w-6">&nbsp;</span>
                                        </section>
                                    </x-table.td>
                                @endif
                                @if ($loop->last)
                                    <x-table.td>
                                        <section class="w-full space-x-2 flex flex-row items-center justify-between">
                                            <span class="text-center w-6">
                                                {{ $this->sumTotalOf('hours', $this->totals[$key], $this->weeklyPeriods[$key]) }}
                                            </span>
                                            <span class="text-center w-6">
                                                {{ $this->sumTotalOf('doors', $this->totals[$key], $this->weeklyPeriods[$key]) }}
                                            </span>
                                            <span class="text-center w-6">
                                                2
                                            </span>
                                            <span class="text-center w-6">
                                                {{ $this->sumTotalOf('sets', $this->totals[$key], $this->weeklyPeriods[$key]) }}
                                            </span>
                                            <span class="text-center w-6">
                                                SA
                                            </span>
                                            <span class="text-center w-6">
                                                {{ $this->sumTotalOf('set_closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}
                                            </span>
                                            <span class="text-center w-6">
                                                CS
                                            </span>
                                            <span class="text-center w-6">
                                                {{ $this->sumTotalOf('closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}
                                            </span>
                                        </section>
                                    </x-table.td>
                                @endif
                            @endforeach
                        </x-table.tr>
                    </x-slot>
                </x-table>
            </div>
        </section>
    @endforeach
</div>
