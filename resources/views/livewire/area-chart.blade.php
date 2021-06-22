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
                    wire:loading.class.remove="hidden" />
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    document.addEventListener("livewire:load", function(event) {
        window.livewire.hook('element.updated', () => {
            drawAreaChart();
        });
    });

    google.charts.load('current', { packages: ['corechart'] }).then(() => drawAreaChart());

    function drawAreaChart()
    {
        const payload = @this.get('data');

        const data = new google.visualization.DataTable();

        data.addColumn('string', 'Period');
        data.addColumn('number', 'Income');

        Array.from({ length: payload.length }, (_, index) => {
            data.addRow([payload[index]['date'], parseFloat(payload[index]['commission'])])
        });

        const chart = new google.visualization.AreaChart(document.getElementById('area_chart'));

        chart.draw(data, {
            legend: 'none',
            colors: ['#46A049'],
            lineWidth: 2,
            vAxis: { gridlines: { count: 0 }, textPosition: 'out', baselineColor: '#FFF' },
            hAxis: { gridlines: { count: 0 }, textPosition: 'out' },
        });
    }
</script>
