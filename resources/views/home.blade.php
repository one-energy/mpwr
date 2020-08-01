<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
                <div class="px-4 py-5 overflow-y-auto sm:px-6 sm:w-full md:w-2/3">
                    <div class="flex justify-between">
                        <h3 class="text-lg text-gray-900">Projected Income</h3>
                        <a href="#">
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

                    <livewire:area-chart/>

                    <div class="flex justify-between mt-12">
                        <div class="flex justify-start">
                            <h3 class="text-lg text-gray-900">Customers</h3>
                            <a href="{{route('customers.create')}}" class="ml-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                                    <circle cx="12" cy="12" r="10" class="fill-current text-green-light"></circle>
                                    <symbol id="add-customer" viewBox="0 0 25 25">
                                        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"
                                              class="fill-current text-green-base"/>
                                    </symbol>
                                    <use xlink:href="#add-customer" width="12" height="12" y="6" x="6"/>
                                </svg>
                            </a>
                        </div>
                        <form action="{{ route('home') }}">
                            <div class="flex justify-end" x-data="{ sortOptions: false }">
                                <label for="sort_by" class="block mt-1 text-xs font-medium leading-5 text-gray-700">
                                    Sort by:
                                </label>
                                <div class="relative inline-block ml-2 text-left">
                                    <select id="sort_by"
                                            name="sort_by"
                                            onchange="this.form.submit()"
                                            class="block w-full py-1 text-lg text-gray-500 transition duration-150 ease-in-out rounded-lg form-select">
                                        @foreach($sortTypes as $type)
                                            <option value="{{$type['index']}}"
                                                    @if(request('sort_by') == $type['index']) selected @endif>
                                                {{$type['value']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mt-6">
                        @forelse ($customers as $customer)
                            <a href="{{route('customers.show', $customer->id)}}">
                                <div
                                    class="flex grid justify-between grid-cols-4 row-gap-1 col-gap-4 p-2 m-1 border-2 border-gray-200 rounded-lg md:grid-cols-9 hover:bg-gray-50">
                                    <div class="col-span-6 md:col-span-7">
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </div>
                                    <div class="col-span-1 row-span-2 md:col-span-2">
                                        <div
                                            class="@if($customer->is_active != 1) bg-red-500 @else bg-green-base @endif text-white rounded-md py-1 px-1 text-center">
                                            $ {{ $customer->commission }}
                                        </div>
                                    </div>
                                    <div class="col-span-7 text-xs text-gray-600">
                                        {{ $customer->epc }}kW
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="h-96 ">
                                <div class="flex justify-center align-middle">
                                    <div class="text-sm text-center text-gray-700">
                                        <x-svg.draw.empty></x-svg.draw.empty>
                                        No data yet.
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Personal Data -->
                <div class="hidden md:block">
                    <x-profile.show-profile-information/>
                </div>
            </div>
        </div>
    </div>
</x-app.auth>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("visualization", "1", {packages:["corechart", "bar"]});
  google.charts.setOnLoadCallback(drawAreaChart);
  google.charts.setOnLoadCallback(drawFunnelChart);

  function drawAreaChart() {
    var data = google.visualization.arrayToDataTable
        ([['Week', 'Sales', {'type': 'string', 'role': 'style'}],
          [1, 3, null],
          [2, 24.5, null],
          [3, 2, null],
          [4, 3, null],
          [5, 14.5, null],
          [6, 6.5, null],
          [7, 9, null],
          [8, 12, null],
          [9, 55, null],
          [10, 34, null],
          [11, 46, 'point { size: 3; shape-type: circle; fill-color: #46A049; }']
    ]);

    var options = {
      legend: 'none',
      colors: ['#46A049'],
      pointSize: 1,
      vAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
      hAxis: { gridlines: { count: 0 }, textPosition: 'none' },
      chartArea:{left:0, top:0, width:"99%", height:"100%"}
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }

  function drawFunnelChart() {
      var data = google.visualization.arrayToDataTable([
        ["Type",   "#",  { role: "style" }],
        ["Doors",  2000, "color: #46A049"],
        ["Hours",  250,  "color: #7de8a6"],
        ["Sets",   125,  "color: #006400"],
        ["Sits",   85,   "color: #B5B5B5"],
        ["Closes", 22,   "color: #FF6E5D"]
        // ["Type", "Invisible", { role: "style" }, "Data", { role: "style" }],
        // ["Doors", -1000, "color: #46A049", 1000, "color: #46A049"],
        // ["Hours", -125, "color: #7de8a6", 125, "color: #7de8a6"],
        // ["Sets", -62.5, "color: #006400", 62.5, "color: #006400"],
        // ["Sits", -42.5, "color: #B5B5B5", 42.5, "color: #B5B5B5"],
        // ["Closes", -11, "color: #FF6E5D", 11, "color: #FF6E5D"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 0,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        // tooltip: { trigger: 'selection'},
        bar: {groupWidth: "95%"},
        legend: { position: 'top' },
        vAxis: { gridlines: { count: 0 }, textPosition: 'none' },
        hAxis: { gridlines: { count: 0 }, textPosition: 'none', baselineColor: '#FFFFFF' },
        chartArea:{left:0, top:0, width:"100%", height:"100%"},
        isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }

  $(window).resize(function(){
    drawAreaChart();
    drawFunnelChart();
});
</script>