<div>
    @push('styles')
        <style>
            table {
                border-collapse: separate;
            }

            thead th:first-child,
            thead th:last-child {
                z-index: 10;
            }

            thead th:first-child { left: 0; }
            thead th:last-child { right: 0; }

            tbody td:first-child,
            tbody td:last-child {
                position: -webkit-sticky;
                position: sticky;
                background: #FFF;
                z-index: 10;
            }

            tbody td:first-child { left: 0; }
            tbody td:last-child { right: 0; }

            thead th:first-child,
            thead th:last-child {
                position: -webkit-sticky;
                position: sticky;
                background-color: #FFF;
            }

            thead th:first-child  { left: 0; }
            thead th:last-child { right: 0; }

            span[name="pipe"]:not(:last-child)::after {
                content: "";
                top: -15px;
                bottom: 0;
                position: absolute;
                height: 51px;
                border-right: 2px solid #E5E7EB;
                display: inline;
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

        @if ($this->offices->isNotEmpty())
            <div class="flex my-6 md:justify-end md:mb-8 md:mt-5">
                <x-select wire:model="selectedOffice" class="w-full md:w-auto" name="offices" label="Offices">
                    @foreach($this->offices as $office)
                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                    @endforeach
                </x-select>
            </div>
        @endif
    </section>

    <section class="flex justify-end mt-3 mb-2">
        <button
            class="py-2 px-4 focus:outline-none rounded shadow-md text-white bg-green-base"
            wire:loading.class.remove="bg-green-base"
            wire:loading.class="flex items-center disabled bg-opacity-50 pointer-events-none bg-green-500"
            wire:target="save"
            wire:click="save"
        >
            <x-svg.spinner wire:target="save" wire:loading  class="w-5 h-5 mr-2" />
            Save
        </button>
    </section>

    @foreach($this->periodsLabel as $key => $label)
        <section class="mb-10">
            <h3 class="font-bold text-center mb-3">{{ $label }}</h3>
            <div class="overflow-x-auto">
                <x-table id="table">
                    <x-slot name="header">
                        <x-table.th-tr>
                            <x-table.th class="whitespace-no-wrap border-r-2">
                                @lang('Office Members')
                            </x-table.th>
                            @foreach($this->weeklyLabels[$key] as $label)
                                <th class="px-4 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider border-l-2 border-r-2">
                                    <section class="flex flex-col items-center">
                                        <span class="font-bold">@lang($label)</span>
                                        <div class="w-full flex flex-row items-center justify-between" style="margin-left: -10px">
                                            @foreach($this->indicators as $indicator)
                                                <div
                                                    class="cursor-default relative"
                                                    x-data=""
                                                    @mouseenter="$dispatch('open-popover', {ref: '{{ $label . $loop->index }}'})"
                                                    @mouseleave="$dispatch('close-popover', {ref: '{{ $label . $loop->index }}'})"
                                                >
                                                    {{ $indicator['label'] }}
                                                    <x-popover position="bottom" ref="{{ $label . $loop->index }}">
                                                        {{ $indicator['description'] }}
                                                    </x-popover>
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>
                                </th>
                            @endforeach
                            <th class="px-4 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider border-l-2 border-r-2">
                                <section class="flex flex-col items-center">
                                    <span class="font-bold">@lang('Weekly Totals')</span>
                                    <div class="w-full flex flex-row items-center justify-between" style="margin-left: -10px">
                                        @foreach($this->indicators as $indicator)
                                            <div
                                                class="cursor-default relative"
                                                x-data=""
                                                @mouseenter="$dispatch('open-popover', {ref: '{{ $label . $loop->index . $indicator['label'] }}'})"
                                                @mouseleave="$dispatch('close-popover', {ref: '{{ $label . $loop->index . $indicator['label'] }}'})"
                                            >
                                                {{ $indicator['label'] }}
                                                <x-popover position="bottom" ref="{{ $label . $loop->index . $indicator['label'] }}">
                                                    {{ $indicator['description'] }}
                                                </x-popover>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            </th>
                        </x-table.th-tr>
                    </x-slot>

                    <x-slot name="body">
                        @foreach($this->users as $userKey => $user)
                            <x-table.tr class="relative">
                                <x-table.td class="border-2">
                                    {{ $user->full_name }}
                                </x-table.td>

                                {{-- Weekly Columns --}}
                                @foreach($this->weeklyLabels[$key] as $label)
                                    @if (isset($user->dailyNumbers[$label]))
                                        <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b border-gray-200">
                                            <div class="relative space-x-2" x-data="">
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline pointer-events-none"
                                                        value="{{ $user->dailyNumbers[$label][0]->hours_worked }}"
                                                        readonly
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->doors }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'doors', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->hours_knocked }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'hours_knocked', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->sets }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'sets', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->sats }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'sats', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->set_closes }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'set_closes', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->closer_sits }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'closer_sits', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->closes }}"
                                                        x-on:input.debounce.400ms="
                                                            $wire.updateDailyNumber({{ $user->dailyNumbers[$label][0]->id }}, 'closes', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                            </div>
                                        </td>
                                    @else
                                        <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b border-gray-200">
                                            <div class="relative space-x-2" x-data="">
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline pointer-events-none"
                                                        value=""
                                                        readonly
                                                        placeholder="HW"
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="D"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'doors', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="D"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'hours_knocked', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="S"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'sets', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="SA"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'sats', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="SC"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'set_closes', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="CS"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'closer_sits', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                                <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="C"
                                                        x-on:input.debounce.400ms="
                                                            $wire.attachNewDailyEntry({{ $userKey }}, {{ $user->id }}, '{{ $label }}', 'closes', $event.target.value)
                                                        "
                                                    />
                                                </span>
                                            </div>
                                        </td>
                                    @endif
                                @endforeach

                                {{-- Weekly Totals Column --}}
                                <td class="border-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                    <div class="relative space-x-2">
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('hours_worked', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('doors', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('hours_knocked', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('sets', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('sats', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('set_closes', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('closer_sits', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                        <span name="pipe">
                                            <input
                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                readonly
                                                value="{{ $this->sumOf('closes', $user, $this->weeklyPeriods[$key]) }}"
                                            />
                                        </span>
                                    </div>
                                </td>
                            </x-table.tr>
                        @endforeach

                        <x-table.tr class="relative">
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-t-2 border-l-2 border-r-2 border-gray-800">
                                <span class="font-bold">Total</span>
                            </td>
                            @foreach($this->weeklyLabels[$key] as $label)
                                @if ($this->totals[$key][$label])
                                    <td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-l-2 border-r-2 border-t-2 border-gray-800">
                                        <div class="relative space-x-2">
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['hours_worked'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['doors'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['hours_knocked'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['sets'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['sats'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['set_closes'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['closer_sits'] }}"/>
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->totals[$key][$label]['closes'] }}"/>
                                            </span>
                                        </div>
                                    </td>
                                @else
                                    <td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-l-2 border-r-2 border-t-2 border-gray-800">
                                        <div class="relative space-x-2">
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                            <span name="pipe">
                                                <input type="text" class="text-center w-10 inline outline-none pointer-events-none font-bold" readonly value="0"/>
                                            </span>
                                        </div>
                                    </td>
                                @endif
                                @if ($loop->last)
                                    <td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-l-2 border-r-2 border-t-2 border-gray-800">
                                        <div class="relative space-x-2">
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('hours_worked', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('doors', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('hours_knocked', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('sets', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('sats', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('set_closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('closer_sits', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                            <span name="pipe">
                                                <input
                                                    type="text"
                                                    class="text-center w-10 inline outline-none pointer-events-none font-bold"
                                                    readonly
                                                    value="{{ $this->sumTotalOf('closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                />
                                            </span>
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </x-table.tr>
                    </x-slot>
                </x-table>
            </div>
        </section>
    @endforeach
</div>
