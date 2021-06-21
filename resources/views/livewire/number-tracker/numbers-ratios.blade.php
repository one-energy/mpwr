<div>
    <div class="flex justify-between mt-6 md:mt-12">
        <div class="grid w-full grid-cols-2 row-gap-2 col-gap-1 md:grid-cols-4 md:col-gap-4">
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">D.P.S</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->dps}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">HW.P. SET</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->hkps}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">Sit Ratio</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->sitRatios}}
                </div>
            </div>
            <div class="col-span-1 p-3 rounded-md bg-green-light space-y-3">
                <div class="text-base font-semibold uppercase text-green-base">Close Ratio</div>
                <div class="text-xl font-bold text-green-base">
                    {{$this->closeRatio}}
                </div>
            </div>
        </div>
    </div>
</div>
