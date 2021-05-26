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
                top: -16px;
                bottom: 0;
                position: absolute;
                height: 54px;
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

    <x-form :route="route('number-tracking.spreadsheet.updateOrCreate')">
        <section class="flex justify-end mt-3 mb-2">
            @if ($this->users->isNotEmpty())
                <button class="py-2 px-4 focus:outline-none rounded shadow-md text-white bg-green-base" type="submit">
                    Save
                </button>
            @endif
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
                                            <div class="w-full flex flex-row items-center justify-between">
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
                                    @foreach($this->weeklyLabels[$key] as $weeklyKey => $label)
                                        @if (isset($user->dailyNumbers[$label]))
                                            <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b border-gray-200">
                                                <div class="relative space-x-2">
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $user->dailyNumbers[$label][0]->id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $user->id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][user_id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $user->office_id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][office_id]"
                                                        />
                                                    </span>
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
                                                        placeholder="D"
                                                        value="{{ $user->dailyNumbers[$label][0]->doors ?? 0 }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][doors]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->hours_knocked }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][hours_knocked]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        placeholder="S"
                                                        value="{{ $user->dailyNumbers[$label][0]->sets ?? 0 }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sets]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->sats }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sats]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        placeholder="SC"
                                                        value="{{ $user->dailyNumbers[$label][0]->set_closes ?? 0 }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][set_closes]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        value="{{ $user->dailyNumbers[$label][0]->closer_sits }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closer_sits]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text"
                                                        class="text-center w-10 inline"
                                                        placeholder="C"
                                                        value="{{ $user->dailyNumbers[$label][0]->closes ?? 0 }}"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closes]"
                                                    />
                                                </span>
                                                </div>
                                            </td>
                                        @else
                                            <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b border-gray-200">
                                                <div class="relative space-x-2">
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $user->id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][user_id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $user->office_id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][office_id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-10 inline pointer-events-none"
                                                            value="{{ $label }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][date]"
                                                        />
                                                    </span>
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
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][doors]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="D"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][hours_knocked]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="S"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sets]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="SA"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sats]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                    <input
                                                        type="text" class="text-center w-10 inline"
                                                        value=""
                                                        placeholder="SC"
                                                        name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][set_closes]"
                                                    />
                                                </span>
                                                    <span name="pipe">
                                                        <input
                                                            type="text" class="text-center w-10 inline"
                                                            value=""
                                                            placeholder="CS"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closer_sits]"
                                                        />
                                                    </span>
                                                    <span name="pipe">
                                                        <input
                                                            type="text" class="text-center w-10 inline"
                                                            value=""
                                                            placeholder="C"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closes]"
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
                                                <span class="hidden"></span>
                                                <span class="hidden"></span>
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
                                                <span class="hidden"></span>
                                                <span class="hidden"></span>
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
    </x-form>
</div>
