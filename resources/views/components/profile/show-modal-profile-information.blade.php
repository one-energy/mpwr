<div class="px-4 py-5 sm:p-6">
    <div class="flex justify-between">
    <div class="flex justify-start">
        <a href="#" class="flex-shrink-0 group block focus:outline-none">
        <div class="flex items-center">
            <div>
            <img class="inline-block h-16 w-16 rounded-full" src="{{ user()->photo_url }}" alt="" />
            </div>
            <div class="ml-3">
            <p class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                {{ user()->first_name }} {{ user()->last_name }}
            </p>
            <p class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
                {{ user()->office }}
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
                1.67
                </div>
            </div>
        </div>
        <div class="col-span-2 text-xs text-gray-900">
            <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                <div class="col-span-2 text-xs text-gray-900">
                HPS RATIO
                </div>
                <div class="col-span-2 text-xl font-bold text-gray-900">
                2.34
                </div>
            </div>
        </div>
        <div class="col-span-2 text-xs text-gray-900">
            <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                <div class="col-span-2 text-xs text-gray-900">
                SIT RATIO
                </div>
                <div class="col-span-2 text-xl font-bold text-gray-900">
                55%
                </div>
            </div>
        </div>
        <div class="col-span-2 text-xs text-gray-900">
            <div class="flex justify-between grid grid-cols-2 row-gap-1 col-gap-2 border-gray-200 border-2 m-1 p-2 rounded-lg">
                <div class="col-span-2 text-xs text-gray-900">
                CLOSE RATIO
                </div>
                <div class="col-span-2 text-xl font-bold text-gray-900">
                22%
                </div>
            </div>
        </div>
    </div>

    <!-- Funnel Chart -->
    <div class="flex justify-between border-gray-200 border-2 m-1 p-2 rounded-lg">
        <div id="barchart_values" class="max-w-full min-w-full"></div>
    </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    const doors = {!!json_encode(user()-> dailyNumbers->sum('doors')) !!};
    const hours = {!!json_encode(user()-> dailyNumbers->sum('hours')) !!};
    const sits = {!!json_encode(user()->dailyNumbers->sum('sits')) !!};
    const sets = {!!json_encode(user()->dailyNumbers->sum('sets')) !!};
    const set_closes = {!!json_encode(user()->dailyNumbers->sum('set_closes')) !!};
    am4core.ready(function() {
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
        if (doors && hours && sits && sets && set_closes) {
            var chart = am4core.create("chartdiv", am4charts.SlicedChart);
            chart.data = [{
                "name": "doors",
                "value2": doors
            }, {
                "name": "hours",
                "value2": hours
            }, {
                "name": "sits",
                "value2": sits
            }, {
                "name": "sets",
                "value2": sets
            }, {
                "name": "Set Closes",
                "value2": set_closes
            }];
            chart.logo.disabled = true;
            var series1 = chart.series.push(new am4charts.FunnelSeries());
            series1.dataFields.value = "value2";
            series1.dataFields.category = "name";
            series1.labels.template.disabled = true;
        } else {
            document.getElementById("chartdiv").innerHTML = "No data to display";
        }
    }); // end am4core.ready()
</script>