<div>
    @push('styles')
        <style>
            @media only screen and (min-width: 1280px) {
                #mainContainer {
                    max-width: 100%;
                }
            }

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

            div[name="pipe"]:not(:last-child)::after {
                content: "";
                top: -15px;
                bottom: 0;
                right: 0;
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

        <div class="flex space-x-3">
            <div class="flex my-6 md:justify-end md:mb-8 md:mt-5">
                <x-select wire:model="selectedMonth" class="w-full md:w-auto" name="months" label="Months">
                    @for($index = 1; $index <= $this->actualMonth; $index++)
                        <option value="{{$this->monthByIndex($index)->month}}">{{ $this->monthByIndex($index)->monthName }}</option>
                    @endfor
                </x-select>
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
        </div>
    </section>
    @foreach($this->periodsLabel as $key => $label)
        <x-form :route="route('number-tracking.spreadsheet.updateOrCreate')" name="form-{{$key}}">
            <section class="flex justify-end mt-3 mb-2">
                @if ($this->users->isNotEmpty())
                    <button class="py-2 px-4 focus:outline-none rounded shadow-md text-white bg-green-base" type="submit" id="submit-{{$key}}" for="form-{{$key}}">
                        Save
                    </button>
                @endif
            </section>
            <section class="mb-10">
                <h3 class="font-bold text-center mb-3">{{ $label }}</h3>
                <div class="overflow-x-auto">
                    <x-table id="table">
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th class="whitespace-no-wrap border-r-2">
                                    @lang('Office Members')
                                </x-table.th>
                                @foreach($this->weeklyDayLabels[$key] as $label)
                                    <th class="py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 tracking-wider border-l-2 border-r-2">
                                        <p class="font-bold text-center">@lang($label)</p>
                                        <section class="grid grid-cols-8">
                                            @foreach($this->indicators as $indicator)
                                                <div
                                                    class="cursor-default relative text-center"
                                                    x-data=""
                                                    x-on:mouseenter="$dispatch('open-popover', {ref: '{{ $label . $loop->index }}'})"
                                                    x-on:mouseleave="$dispatch('close-popover', {ref: '{{ $label . $loop->index }}'})"
                                                >
                                                    {{ $indicator['label'] }}
                                                    <x-popover position="bottom" ref="{{ $label . $loop->index }}">
                                                        {{ $indicator['description'] }}
                                                    </x-popover>
                                                </div>
                                            @endforeach
                                        </section>
                                    </th>
                                @endforeach
                                <th class="px-4 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider border-l-2 border-r-2">
                                    <section class="flex flex-col items-center">
                                        <span class="font-bold">@lang('Weekly Totals')</span>
                                        <div class="w-full flex flex-row items-center justify-between" style="margin-left: -10px">
                                            @foreach($this->indicators as $indicator)
                                                <div class="cursor-default relative">
                                                    {{ $indicator['label'] }}
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
                                    @foreach($this->weeklyMonthLabels[$key] as $weeklyKey => $label)
                                        @if (isset($user->dailyNumbers[$label]))
                                            <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b border-gray-200">
                                                <section style="min-width: 400px" class="grid grid-cols-8 relative">
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
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-10 inline pointer-events-none"
                                                                value="{{ $user->dailyNumbers[$label][0]->hours_worked }}"
                                                                readonly
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->doors ?? 0 }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][doors]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->hours_knocked }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][hours_knocked]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->sets ?? 0 }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sets]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                            <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->sats }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sats]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->set_closes ?? 0 }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][set_closes]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->closer_sits }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closer_sits]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text"
                                                                class="text-center w-10 inline"
                                                                value="{{ $user->dailyNumbers[$label][0]->closes ?? 0 }}"
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closes]"
                                                            />
                                                        </span>
                                                    </div>
                                                </section>
                                            </td>
                                        @else
                                            <td class="border-t-2 border-l-2 border-r-2 py-4 whitespace-no-wrap text-sm text-gray-800 md:border-b border-gray-200">
                                                <section style="min-width: 400px" class="grid grid-cols-8 relative">
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-9 inline pointer-events-none"
                                                            value="{{ $user->id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][user_id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-9 inline pointer-events-none"
                                                            value="{{ $user->office_id }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][office_id]"
                                                        />
                                                    </span>
                                                    <span class="hidden">
                                                        <input
                                                            type="text" class="text-center w-9 inline pointer-events-none"
                                                            value="{{ $label }}"
                                                            name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][date]"
                                                        />
                                                    </span>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline pointer-events-none"
                                                                value=""
                                                                readonly
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][doors]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][hours_knocked]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sets]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][sats]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][set_closes]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closer_sits]"
                                                            />
                                                        </span>
                                                    </div>
                                                    <div class="relative" name="pipe">
                                                        <span class="block text-center">
                                                            <input
                                                                type="text" class="text-center w-9 inline"
                                                                value=""
                                                                name="dailyNumbers[{{ $key }}][{{ $weeklyKey }}][{{ $userKey }}][closes]"
                                                            />
                                                        </span>
                                                    </div>
                                                </section>
                                            </td>
                                        @endif
                                    @endforeach

                                    {{-- Weekly Totals Column --}}
                                    <td class="border-2 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                                        <section style="min-width: 400px" class="grid grid-cols-8 relative">
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('hours_worked', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('doors', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('hours_knocked', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('sets', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('sats', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('set_closes', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span class="block text-center">
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('closer_sits', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text" class="text-center w-9 inline pointer-events-none"
                                                        readonly
                                                        value="{{ $this->sumOf('closes', $user, $this->weeklyPeriods[$key]) }}"
                                                    />
                                                </span>
                                            </div>
                                        </section>
                                    </td>
                                </x-table.tr>
                            @endforeach

                            <x-table.tr class="relative">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-2 border-gray-800">
                                    <span class="font-bold">Total</span>
                                </td>
                                @foreach($this->weeklyMonthLabels[$key] as $label)
                                    <td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-2 border-gray-800">
                                        <section class="relative text-center" style="display: grid; grid-template-columns: repeat(8, 1fr)">
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['hours_worked'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['doors'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['hours_knocked'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['sets'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['sats'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['set_closes'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['closer_sits'] }}"/>
                                                </span>
                                            </div>
                                            <div class="relative" name="pipe">
                                                <span>
                                                    <input
                                                        type="text"
                                                        class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                        readonly
                                                        value="{{ $this->totals[$key][$label]['closes'] }}"/>
                                                </span>
                                            </div>
                                        </section>
                                    </td>
                                    @if ($loop->last)
                                        <td class="py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 border-2 border-gray-800">
                                            <section class="relative text-center" style="display: grid; grid-template-columns: repeat(8, 1fr)">
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('hours_worked', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('doors', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('hours_knocked', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('sets', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('sats', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('set_closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('closer_sits', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                                <div class="relative" name="pipe">
                                                    <span>
                                                        <input
                                                            type="text"
                                                            class="text-center w-9 inline outline-none pointer-events-none font-bold"
                                                            readonly
                                                            value="{{ $this->sumTotalOf('closes', $this->totals[$key], $this->weeklyPeriods[$key]) }}"
                                                        />
                                                    </span>
                                                </div>
                                            </section>
                                        </td>
                                    @endif
                                @endforeach
                            </x-table.tr>
                        </x-slot>
                    </x-table>
                </div>
            </section>
        </x-form>
    @endforeach
</div>
