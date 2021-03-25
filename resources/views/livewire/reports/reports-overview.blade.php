<div>
    <div class="mt-5 flex justify-between">
        <div>
            <x-input name="pending_customer" label="Peding Customer" labelInside/>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <x-input class="col-span-2" name="range_date" label="Range Date" labelInside/>
            <x-input name="start_date" label="From" labelInside/>
            <x-input name="end_date" label="To" labelInside/>
        </div>
    </div>
    <div class="grid justify-items-center mt-6">
        <div class=" w-1/2 rounded-md border border-gray-200">
            <x-table>
                <x-slot name="header">
                    <x-table.th-tr>
                        <x-table.th></x-table.th>
                        <x-table.th by="setter_rate">
                            @lang('Setter Rate')
                        </x-table.th>
                        <x-table.th by="system_size">
                            @lang('System Size')
                        </x-table.th>
                        <x-table.th by="my_setter_commission">
                            @lang('My Setter Comission')
                        </x-table.th>
                    </x-table.th-tr>
                </x-slot>
                <x-slot name="body">
                    <x-table.tr >
                        <x-table.td>Average</x-table.td>
                        <x-table.td>{{user()->pay}}</x-table.td>
                        <x-table.td></x-table.td>
                        <x-table.td>Average</x-table.td>
                    </x-table.tr>
                    <x-table.tr class="bg-gray-100">
                        <x-table.td>Total</x-table.td>
                        <x-table.td>-</x-table.td>
                        <x-table.td>Total</x-table.td>
                        <x-table.td>Total</x-table.td>
                    </x-table.tr>
                </x-slot>
            </x-table>
        </div>
    </div>
    <div class="mt-6">
        <x-search :search="$search"/>
    </div>
    <div class="mt-6">
        <x-table :pagination="$customers->links()">
            <x-slot name="header">
                <x-table.th-tr>
                    <x-table.th by="home_owner">
                        @lang('Home Owner')
                    </x-table.th>
                    <x-table.th by="sold_date">
                        @lang('Sold Date')
                    </x-table.th>
                    <x-table.th by="setter">
                        @lang('Setter')
                    </x-table.th>
                    <x-table.th by="setter_date">
                        @lang('Setter Date')
                    </x-table.th>
                    <x-table.th by="closer">
                        @lang('Closer')
                    </x-table.th>
                    <x-table.th by="closer_rate">
                        @lang('Closer Rate')
                    </x-table.th>
                    <x-table.th by="system_size">
                        @lang('System Size')
                    </x-table.th>
                    <x-table.th by="setter_commission">
                        @lang('Setter Commission')
                    </x-table.th>
                </x-table.th-tr>
            </x-slot>
            <x-slot name="body">
                @foreach($customers as $customer)
                    <x-table.tr >
                        <x-table.td>{{$customer->first_name}} {{$customer->last_name}}</x-table.td>
                        <x-table.td>{{$customer->date_of_sale->format('M-d')}}</x-table.td>
                        <x-table.td>{{$customer->userSetter?->first_name}} {{$customer->userSetter?->last_name}}</x-table.td>
                        <x-table.td>{{$customer->userSetter?->pay ? '$' : '-'}}{{$customer->userSetter?->pay}}</x-table.td>
                        <x-table.td>{{$customer->userSalesRep?->first_name}} {{$customer->userSalesRep?->last_name}}</x-table.td>
                        <x-table.td>{{$customer->userSalesRep?->pay ? '$' : '-'}}{{$customer->userSalesRep?->pay}}</x-table.td>
                        <x-table.td>{{$customer->system_size}}</x-table.td>
                        <x-table.td>${{$customer->userSetter?->pay * ($customer->system_size * 1000)}}</x-table.td>
                    </x-table.tr>
                @endforeach
            </x-slot>
        </x-table>
    </div>

</div>
