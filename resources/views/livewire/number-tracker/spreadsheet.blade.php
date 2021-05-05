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
                                        <span>HW</span>
                                        <span>D</span>
                                        <span>HK</span>
                                        <span>S</span>
                                        <span>SA</span>
                                        <span>SC</span>
                                        <span>CS</span>
                                        <span>C</span>
                                    </div>
                                </section>
                            </x-table.th>
                        </x-table.th-tr>
                    </x-slot>

                    <x-slot name="body">
                        @foreach($this->users as $user)
                            <x-table.tr class="relative">
                                <x-table.td>{{ $user->full_name }}</x-table.td>
                                @foreach($this->weeklyLabels[$key] as $label)
                                    @if (isset($user->dailyNumbers[$label]))
                                        <x-table.td>
                                            <section class="w-full flex flex-row items-center justify-between">
                                                <span>{{ $user->dailyNumbers[$label][0]->doors }}</span>
                                                <span>2</span>
                                                <span>2</span>
                                                <span>2</span>
                                                <span>2</span>
                                                <span>2</span>
                                                <span>2</span>
                                            </section>
                                        </x-table.td>
                                    @else
                                        <x-table.td>
                                            <section class="w-full flex flex-row items-center justify-between">
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                                <span>&nbsp;</span>
                                            </section>
                                        </x-table.td>
                                    @endif
                                @endforeach
                                <x-table.td>
                                    <section class="w-full flex flex-row items-center justify-between">
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                        <span>0</span>
                                    </section>
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-slot>
                </x-table>
            </div>
        </section>
    @endforeach
</div>
