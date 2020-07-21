<div>
    <div id="area_chart" class="max-w-full"></div>

    <div x-data="{ openTab: 'w',
        active: 'border-b-2 border-green-base text-green-base',
        inactive: 'text-gray-900 hover:text-gray-500' }">

        <ul class="flex border-b">
            <li @click="openTab = 'w'" class="-mb-px mr-4">
                <a :class="openTab === 'w' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click="setPeriod('w')">W</a>
            </li>
            <li  @click="openTab = 'm'" class="-mb-px mr-4">
                <a :class="openTab === 'm' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click="setPeriod('m')">M</a>
            </li>
            <li  @click="openTab = 's'" class="-mb-px mr-4">
                <a :class="openTab === 's' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click="setPeriod('s')">S</a>
            </li>
            <li  @click="openTab = 'y'" class="-mb-px mr-4">
                <a :class="openTab === 'y' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click="setPeriod('y')">Y</a>
            </li>
            <li  @click="openTab = 'all'" class="-mb-px mr-4">
                <a :class="openTab === 'all' ? active : inactive" class="bg-white inline-block py-2 px-4 font-semibold cursor-pointer" wire:click="setPeriod('all')">All</a>
            </li>
        </ul>
    </div>
</div>

<livewire:scripts>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawAreaChart);

        function drawAreaChart()
        {
            var data = google.visualization.arrayToDataTable([
                ['Period', 'Income'],
                <?php
                    foreach((array) $customers as $customer) {
                        echo "['".$customer->created_at."', ".$customer->comission."],";
                    }
                ?>
            ]);

            var options = {
                legend: 'none',
                colors: ['#46A049'],
                pointSize: 1,
                vAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
                hAxis: { gridlines: { count: 0 }, textPosition: 'none' },
                chartArea:{left:0, top:0, width:"99%", height:"100%"}
            };

            var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
            chart.draw(data, options);
        }
    </script>
</livewire:scripts>