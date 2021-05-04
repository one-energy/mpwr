<x-app.auth :title="__('Weekly Spreadsheet')">
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

    <div>
        <div class="max-w-8xl mx-auto py-5 sm:px-6 lg:px-8">
            <a href="{{ route('number-tracking.index') }}" class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
                < Back to Tracker Overview
            </a>
        </div>

        <section class="overflow-x-auto">
            <x-table id="table">
                <x-slot name="header">
                    <x-table.th-tr>
                        <x-table.th class="whitespace-no-wrap sticky">
                            @lang('Office Members')
                        </x-table.th>
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 02th')</span>
                                <div class="w-full flex flex-row items-center justify-between space-x-3">
                                    @foreach($indicators as $indicator)
                                        <div
                                            class="cursor-default"
                                            x-data=""
                                            @mouseenter="$dispatch('open-popover', {ref: '{{ $indicator['label'] }}'})"
                                            @mouseleave="$dispatch('close-popover', {ref: '{{ $indicator['label'] }}'})"
                                        >
                                            {{ $indicator['label'] }}
                                           <x-popover ref="{{ $indicator['label'] }}">{{ $indicator['description'] }}</x-popover>
                                        </div>
                                    @endforeach
                                </div>

                            </section>
                        </x-table.th>
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 03th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 04th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 05th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 06th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 07th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 08th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 08th')</span>
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
                        <x-table.th class="whitespace-no-wrap">
                            <section class="flex flex-col items-center">
                                <span class="font-bold">@lang('May 08th')</span>
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
                    @foreach (collect(['John Doe', 'Joseph Mcfly', 'Mary Ann', 'Tifa Lockhart', 'Red XIII', 'Total']) as $name)
                        <x-table.tr class="relative">
                            <x-table.td>{{ $name }}</x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                            <x-table.td>
                                <section class="w-full flex flex-row items-center justify-between">
                                    <span>12</span>
                                    <span>23</span>
                                    <span>5</span>
                                    <span>4</span>
                                    <span>1</span>
                                    <span>0</span>
                                    <span>0</span>
                                    <span>0</span>
                                </section>
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-slot>
            </x-table>
        </section>
    </div>

    {{-- @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                [...document.querySelectorAll('#table td:first-child')]
                    .forEach(element => element.style = 'position: sticky; top: 0; background: #000; color: #FFF;');
            });
        </script>
    @endpush --}}
</x-app.auth>
