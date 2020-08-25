<div>
    <div class="flex justify-between">
        <h3 class="text-lg text-gray-900">{{ $chartTitle }}</h3>
        <a wire:click.prevent="toggle" class="cursor-pointer">
            <x-svg.panel></x-svg.panel>
        </a>
    </div>
    <div class="font-bold text-lg">
        ${{ number_format($totalIncome, 2) }}
    </div>
    <div class="flex font-semibold text-xs @if($comparativeIncome >= 0) text-green-base @else text-red-600 @endif">
        @if($period != 'all')
            @if($comparativeIncome >= 0)
                <x-svg.arrow-up></x-svg.arrow-up>
            @else
                <x-svg.arrow-down></x-svg.arrow-up>
            @endif
            <span>
                ${{ number_format($comparativeIncome, 2) }} ({{ number_format($comparativeIncomePercentage, 2) }}%)
            </span>
        @endif
    </div>
    <div wire:loading.class.add="relative bg-gray-700 opacity-75">
        <div id="area_chart" class="max-w-full"></div>
    </div>

    <div x-data="{ openTab: 'w',
        active: 'border-b-2 border-green-base text-green-base',
        inactive: 'text-gray-900 hover:text-gray-500' }">

        <ul class="flex border-b">
            <li @click="openTab = 'w'" class="-mb-px mr-4">
                <a :class="openTab === 'w' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click.prevent="setPeriod('w')">W</a>
            </li>
            <li @click="openTab = 'm'" class="-mb-px mr-4">
                <a :class="openTab === 'm' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click.prevent="setPeriod('m')">M</a>
            </li>
            <li @click="openTab = 's'" class="-mb-px mr-4">
                <a :class="openTab === 's' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click.prevent="setPeriod('s')">S</a>
            </li>
            <li @click="openTab = 'y'" class="-mb-px mr-4">
                <a :class="openTab === 'y' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click.prevent="setPeriod('y')">Y</a>
            </li>
            <li @click="openTab = 'all'" class="-mb-px mr-4">
                <a :class="openTab === 'all' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click.prevent="setPeriod('all')">All</a>
            </li>
            <li>
                <x-svg.spinner 
                    color="#9fa6b2" 
                    class="relative hidden top-2 w-6" 
                    wire:loading.class.remove="hidden">
                </x-svg.spinner>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    document.addEventListener("livewire:load", function(event) {
        window.livewire.hook('afterDomUpdate', () => {
            drawAreaChart();
        });
    });

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawAreaChart);

    function drawAreaChart()
    {
        var period = @this.get('incomeDate');
        var income = @this.get('income');

        var data = new google.visualization.DataTable();
        
        data.addColumn('string', 'Period');
        data.addColumn('number', 'Income');

        for (var i=0; i < income.length; i++) {
            data.addRow([period[i], parseFloat(income[i])]);
            console.log([period[i], income[i]]);
        }

        var options = {
            legend: 'none',
            colors: ['#46A049'],
            lineWidth: 1,
            vAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
            hAxis: { gridlines: { count: 0 }, textPosition: 'none' },
            chartArea:{left:0, top:0, width:"99%", height:"100%"}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
        chart.draw(data, options);
    }
</script>
