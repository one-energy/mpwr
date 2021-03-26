<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="grid grid-cols-2 mt-5 gap-4">
                <div class="col-span-2 md:col-span-1 md:w-1/2">
                    <x-select name="pending_customer" labelInside>
                        <option value="0" selected>Pending Customer</option>
                    </x-select>
                </div>
                <div class="justify-self-end col-span-2 grid grid-cols-2 gap-2 md:col-span-1 md:w-1/2">
                    <x-select class="col-span-2" name="range_date" labelInside>
                        <option value="0">Today</option>
                        <option value="1">Week to Date</option>
                        <option value="2">Last Week</option>
                        <option value="3">Month to Date</option>
                        <option value="4">Last Month</option>
                        <option value="5">Quarter to Date</option>
                        <option value="6">Last Quarter</option>
                        <option value="7" selected>Year to Date</option>
                        <option value="8">Last Year</option>
                        <option value="9">Custom</option>
                    </x-select>
                    <x-input-calendar class="w-full" wire name="startDate" label="From" labelInside/>
                    <x-input-calendar wire name="endDate" label="To" labelInside/>
                </div>
            </div>
            <div class="grid justify-items-center mt-6 overflow-x-auto">
                <div class="flex flex-col rounded-md border border-gray-200">
                    <x-table>
                        <x-slot name="header">
                            <x-table.th-tr>
                                <x-table.th></x-table.th>
                                <x-table.th by="setter_rate">
                                    @lang('Setter Rate')
                                </x-table.th>
                                <x-table.th by="system_size">
                                    @lang('My Set System Size')
                                </x-table.th>
                                <x-table.th by="my_setter_commission">
                                    @lang('My Setter Comission')
                                </x-table.th>
                                <x-table.th by="my_recruter_commission">
                                    @lang('My Recruter Comission')
                                </x-table.th>
                                <x-table.th by="my_total_commission">
                                    @lang('My Total Comission')
                                </x-table.th>
                            </x-table.th-tr>
                        </x-slot>
                        <x-slot name="body">
                            <x-table.tr >
                                <x-table.td>Average</x-table.td>
                                <x-table.td>{{$userCustomers->count() ? '$ ' . $userCustomers->sum('userSetter.pay')/$userCustomers->count() : '-'}}</x-table.td>
                                <x-table.td>{{$userCustomers->count() ? $userCustomers->sum('system_size')/$userCustomers->count() : '-'}}</x-table.td>
                                <x-table.td>-</x-table.td>
                                <x-table.td>-</x-table.td>
                                <x-table.td>-</x-table.td>
                            </x-table.tr>
                            <x-table.tr class="bg-gray-100">
                                <x-table.td>Total</x-table.td>
                                <x-table.td class="font-bold">$ {{$userCustomers->sum('userSetter.pay')}}</x-table.td>
                                <x-table.td class="font-bold">{{$userCustomers->sum('system_size')}}</x-table.td>
                                <x-table.td class="font-bold">-</x-table.td>
                                <x-table.td class="font-bold">-</x-table.td>
                                <x-table.td class="font-bold">-</x-table.td>
                            </x-table.tr>
                        </x-slot>
                    </x-table>
                </div>
            </div>
            <div class="mt-6">
                <x-search :search="$search"/>
            </div>
            <div class="mt-6 overflow-x-auto">
                <div class="flex flex-col">
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
                                <x-table.th by="setter_rate">
                                    @lang('Setter Rate')
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
        </div>
    </div>
</div>
