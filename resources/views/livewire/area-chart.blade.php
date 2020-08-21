<div>
    <div class="flex justify-between">
        <h3 class="text-lg text-gray-900">{{ $chartTitle }}</h3>
        <a wire:click.prevent="toggle" class="cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                <circle cx="20" cy="20" r="14" class="fill-current text-green-base"></circle>
                <symbol id="panel" viewBox="0 0 25 25">
                    <path
                        d="M6 18h-2v5h-2v-5h-2v-3h6v3zm-2-17h-2v12h2v-12zm11 7h-6v3h2v12h2v-12h2v-3zm-2-7h-2v5h2v-5zm11 14h-6v3h2v5h2v-5h2v-3zm-2-14h-2v12h2v-12z"
                        class="text-white fill-current"/>
                </symbol>
                <use xlink:href="#panel" width="14" height="14" y="13" x="13"/>
            </svg>
        </a>
    </div>
    <div class="font-bold text-lg">
        ${{ number_format($totalIncome, 2) }}
    </div>
    <div class="flex font-semibold text-xs @if($comparativeIncome >= 0) text-green-base @else text-red-500 @endif">
        @if($period != 'all')
            <svg xmlns="http://www.w3.org/2000/svg" transform='@if($comparativeIncome >= 0) rotate(-45) @else rotate(45) @endif' width="20" height="20" viewBox="0 0 20 20">
                <symbol id="arrow" viewBox="0 0 24 24">
                <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="fill-current" />
                </symbol>
                <use xlink:href="#arrow" width="12" height="12" @if($comparativeIncome >= 0) y="6" x="6" @else y="4" x="4" @endif />
            </svg>
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
                <svg class="relative hidden top-2 w-6"
                    wire:loading.class.remove="hidden"
                    viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#9fa6b2">
                    <g fill="none">
                        <g transform="translate(1 1)" stroke-width="2">
                            <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
                            <path d="M36 18c0-9.94-8.06-18-18-18">
                                <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 18 18"
                                    to="360 18 18"
                                    dur="1s"
                                    repeatCount="indefinite" />
                            </path>
                        </g>
                    </g>
                </svg>
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
