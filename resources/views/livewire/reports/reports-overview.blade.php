<div>
    <div class="mt-5 flex gap-2">
        <x-input name="Date" label="Year to date" labelInside/>
        <x-input name="Date" label="From" labelInside/>
        <x-input name="Date" label="To" labelInside/>
        <x-input name="Date" label="Active" labelInside/>
        <x-search class="w-full" :search="$search"/>
    </div>
    <div>
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
                    <x-table.td>Average</x-table.td>
                    <x-table.td>Average</x-table.td>
                    <x-table.td>Average</x-table.td>
                </x-table.tr>
                <x-table.tr >
                    <x-table.td>Total</x-table.td>
                    <x-table.td>Total</x-table.td>
                    <x-table.td>Total</x-table.td>
                    <x-table.td>Total</x-table.td>
                </x-table.tr>
            </x-slot>
        </x-table>
    </div>
</div>
