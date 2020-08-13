<div>
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between">
        <div class="flex justify-start">
            <a href="#" class="flex-shrink-0 group block focus:outline-none">
            <div class="flex items-center">
                <div>
                <img class="inline-block h-16 w-16 rounded-full" src="{{ $user->photo_url }}" alt="" />
                </div>
                <div class="ml-3">
                <p class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                    {{ $user->first_name }} {{ $user->last_name }}
                </p>
                <p class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
                    {{ $user->office }}
                </p>
                </div>
            </div>
            </a>
        </div>
        </div>
        <div class="mt-6">
        <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-2 m-1 p-2">
            <div class="col-span-2 text-xs text-gray-900">
                <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-2 text-xs text-gray-900">
                        DPS RATIO
                    </div>
                    <div class="col-span-2 text-xl font-bold text-gray-900">
                        {{ number_format($dpsRatio) }}
                    </div>
                </div>
            </div>
            <div class="col-span-2 text-xs text-gray-900">
                <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-2 text-xs text-gray-900">
                        HPS RATIO
                    </div>
                    <div class="col-span-2 text-xl font-bold text-gray-900">
                        {{ number_format($hpsRatio) }}
                    </div>
                </div>
            </div>
            <div class="col-span-2 text-xs text-gray-900">
                <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-2 text-xs text-gray-900">
                        SIT RATIO
                    </div>
                    <div class="col-span-2 text-xl font-bold text-gray-900">
                        {{ number_format($sitRatio) }}%
                    </div>
                </div>
            </div>
            <div class="col-span-2 text-xs text-gray-900">
                <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-2 text-xs text-gray-900">
                        CLOSE RATIO
                    </div>
                    <div class="col-span-2 text-xl font-bold text-gray-900">
                        {{ number_format($closeRatio) }}%
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Bar Chart -->
        <div class="flex justify-between border-gray-200 border-2 m-1 p-2 rounded-lg">
            <div id="bar_chart" class="max-w-full min-w-full"></div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load("current", {'packages':["bar"]});
    google.charts.setOnLoadCallback(drawBarChart);

    function drawBarChart() {
        var doors  = @this.get('totalDoors');
        var hours  = @this.get('totalHours');
        var sets   = @this.get('totalSets');
        var sits   = @this.get('totalSits');
        var closes = @this.get('totalCloses');

        console.log(doors);
        console.log(hours);
        console.log(sets);
        console.log(sits);
        console.log(closes);

        var data = google.visualization.arrayToDataTable([
            ["Type",   "Quantity", { role: "style" }],
            ["Doors",  doors,      "color: #46A049"],
            ["Hours",  hours,      "color: #7de8a6"],
            ["Sets",   sets,       "color: #006400"],
            ["Sits",   sits,       "color: #B5B5B5"],
            ["Closes", closes,     "color: #FF6E5D"]
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 0,
                type: "string",
                role: "annotation" },
            2]);

        var options = {
            bar: {groupWidth: "95%"},
            legend: { position: 'top' },
            vAxis: { gridlines: { count: 0 }, textPosition: 'none' },
            hAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
            chartArea:{left:0, top:0, width:"100%", height:"100%"},
            isStacked: true
        };
        var chart = new google.visualization.BarChart(document.getElementById("bar_chart"));
        chart.draw(view, options);
  }
</script>