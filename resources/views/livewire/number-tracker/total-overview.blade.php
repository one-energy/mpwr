<section>
    <div class="flex justify-between items-center mt-6">
        <h2 class="text-lg text-gray-900">Total Overviews</h2>

        @if (user()->notHaveRoles(['Setter', 'Sales Rep']))
            <a
                href="{{ route('number-tracking.spreadsheet') }}"
                class="py-2 px-3 focus:outline-none rounded shadow-md text-white bg-green-base"
                title="Spreadsheet Page"
            >
                <x-svg.spreadsheet class="w-5 h-5 text-white fill-current"/>
            </a>
        @endif
    </div>

    <div class="mt-3 overflow-auto flex flex-row space-x-4 p-3">
        @foreach($this->overviewFields as $field)
            <div style="min-width: fit-content; flex: 0 0 auto" class="w-48 border-2 border-gray-200 rounded-md p-3 space-y-1">
                <div class="text-base font-semibold uppercase">
                    {{ $field }}
                </div>
                <div class="text-xl font-bold">
                    {{ $this->sumDailyNumbersBy($field) }}
                </div>
                <div class="flex text-xs font-semibold text-green-base">
                    @if($this->differenceFromLastDailyNumbersBy($field) >= 0)
                        <x-svg.arrow-up class="text-green-base"/>
                    @else
                        <x-svg.arrow-down class="text-red-600"/>
                    @endif
                    <span class="text-base
                        @if($this->differenceFromLastDailyNumbersBy($field) >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                        {{ $this->differenceFromLastDailyNumbersBy($field) }}
                    </span>
                </div>
            </div>
        @endforeach

        <div style="min-width: fit-content; flex: 0 0 auto" class="w-56 border-2 border-gray-200 rounded-md p-3 space-y-1">
            <div class="text-base font-semibold text-gray-900 uppercase">Closes</div>
            <div class="grid grid-cols-4 gap-1">
                <div class="text-sm self-center col-span-3">
                    <span>Set</span>
                    <span class="text-xl font-bold text-gray-900 ml-2">
                        {{ $this->sumDailyNumbersBy('set_closes') }}
                    </span>
                </div>
                <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                    @if($this->differenceFromLastDailyNumbersBy('set_closes') >= 0)
                        <x-svg.arrow-up class="text-green-base"/>
                    @else
                        <x-svg.arrow-down class="text-red-600"/>
                    @endif
                    <span class="text-base
                        @if($this->differenceFromLastDailyNumbersBy('set_closes') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                        {{ $this->differenceFromLastDailyNumbersBy('set_closes') }}
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-1">
                <div class="text-sm self-center col-span-3">
                    <span>SG</span>
                    <span class="text-xl font-bold text-gray-900 ml-2">
                        {{ $this->sumDailyNumbersBy('closes') }}
                    </span>
                </div>
                <div class="flex text-xs font-semibold place-self-end col-span-1 items-center">
                    @if($this->differenceFromLastDailyNumbersBy('closes') >= 0)
                        <x-svg.arrow-up class="text-green-base"/>
                    @else
                        <x-svg.arrow-down class="text-red-600"/>
                    @endif
                    <span class="text-base
                        @if($this->differenceFromLastDailyNumbersBy('closes') >= 0)
                            text-green-base
                        @else
                            text-red-600
                        @endif
                    ">
                        {{ $this->differenceFromLastDailyNumbersBy('closes') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
