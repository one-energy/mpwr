<div>
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-5 sm:px-6">
            <div class="grid grid-cols-2 mt-5 gap-4">
                <div class="col-span-2 md:col-span-1 md:w-1/2">
                    <x-select wire:model="selectedStatus" name="pending_customer" labelInside>
                        @foreach($status as $key => $selectStatus)
                            <option value="{{$key}}">{{$selectStatus}}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="justify-self-end col-span-2 grid grid-cols-2 gap-2 md:col-span-1 md:w-1/2">
                    <x-select class="col-span-2" name="range_date" wire:model="rangeType" labelInside>
                        @foreach($ranges as $range)
                            <option value="{{$range['value']}}">{{$range['title']}}</option>
                        @endforeach
                    </x-select>
                    @if($rangeType == "custom")
                        <div class="grid grid-cols-2 gap-2 col-span-2 ">
                            <x-input-calendar key="startDate" wire name="startDate" label="From" :value="$startDate"/>
                            <x-input-calendar key="finalDate" wire name="finalDate" label="From" :value="$finalDate"/>
                        </div>
                    @endif()
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
                                        @if (user()->role == "Setter")
                                            @lang('My Set System Size (kW)')
                                        @endif
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
                                <x-table.td>
                                    {{$customersOfUser?->avg('setter_fee') ? '$ ' . $customersOfUser?->avg('setter_fee') : '-' }}
                                </x-table.td>
                                <x-table.td>
                                    {{$customersOfUser?->avg('system_size') ? $customersOfUser?->avg('system_size') : '-' }}
                                </x-table.td>
                                <x-table.td>
                                    {{$this->getAvgSetterCommission($customersOfUser) ? '$ ' . $this->getAvgSetterCommission($customersOfUser) : '-'}}
                                </x-table.td>
                                <x-table.td>
                                    {{$this->getAvgRecruiterCommission($customersOfSalesRepsRecuited) ? '$ ' . $this->getAvgRecruiterCommission($customersOfSalesRepsRecuited) : '-'}}
                                </x-table.td>
                                <x-table.td>-</x-table.td>
                            </x-table.tr>
                            <x-table.tr class="bg-gray-100">
                                <x-table.td>Total</x-table.td>
                                <x-table.td class="font-bold">-</x-table.td>
                                <x-table.td class="font-bold">
                                    {{$customersOfUser->sum('system_size') > 0 ? $customersOfUser->sum('system_size') : '-' }}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{$this->getSumSetterCommission($customersOfUser) ? '$ ' . $this->getSumSetterCommission($customersOfUser) : '-'}}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{  $this->getSumRecruiterCommission($customersOfSalesRepsRecuited) ? '$ ' . $this->getSumRecruiterCommission($customersOfSalesRepsRecuited) : '-'}}
                                </x-table.td>
                                <x-table.td class="font-bold">
                                    {{  $this->getUserTotalCommission() ? '$ ' . $this->getUserTotalCommission() : '-'}}
                                </x-table.td>
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
                                    <x-table.td>{{$customer->userSetter?->first_name ?? '-'}} {{$customer->userSetter?->last_name}}</x-table.td>
                                    <x-table.td>{{$customer->setter_fee ? '$' : '-'}}{{$customer->setter_fee}}</x-table.td>
                                    <x-table.td>{{$customer->userSalesRep?->first_name ?? '-'}} {{$customer->userSalesRep?->last_name}}</x-table.td>
                                    <x-table.td>{{$customer->userSalesRep?->pay ? '$' : '-'}}{{$customer->userSalesRep?->pay}}</x-table.td>
                                    <x-table.td>{{$customer->system_size ?? '-'}}</x-table.td>
                                    <x-table.td>${{$this->getSetterCommission($customer)}}</x-table.td>
                                </x-table.tr>
                            @endforeach
                        </x-slot>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
