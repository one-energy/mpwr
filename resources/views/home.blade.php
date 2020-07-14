<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
            <div class="px-4 py-5 sm:px-6 sm:w-full md:w-2/3 overflow-auto">
                <div class="flex justify-between">
                <h3 class="text-lg text-gray-900">Projected Income</h3>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
                    <circle cx="20" cy="20" r="14" class="text-green-base fill-current"></circle>
                    <symbol id="panel" viewBox="0 0 25 25">
                        <path d="M6 18h-2v5h-2v-5h-2v-3h6v3zm-2-17h-2v12h2v-12zm11 7h-6v3h2v12h2v-12h2v-3zm-2-7h-2v5h2v-5zm11 14h-6v3h2v5h2v-5h2v-3zm-2-14h-2v12h2v-12z" class="text-white fill-current" />
                    </symbol>
                    <use xlink:href="#panel" width="14" height="14" y="13" x="13" />
                    </svg>
                </a>
                </div>
                <div class="font-bold text-lg">
                $239,459
                </div>
                <div class="flex font-semibold text-xs text-green-base">
                <svg xmlns="http://www.w3.org/2000/svg" transform='rotate(-45)' width="20" height="20" viewBox="0 0 20 20">
                    <symbol id="arrow" viewBox="0 0 24 24">
                    <path d="M12.068.016l-3.717 3.698 5.263 5.286h-13.614v6h13.614l-5.295 5.317 3.718 3.699 11.963-12.016z" class="text-gree-base fill-current" />
                    </symbol>
                    <use xlink:href="#arrow" width="12" height="12" y="6" x="6" />
                </svg>
                <span>
                    $132,421 (50.23%)
                </span>
                </div>
        
                <!-- Area Chart -->
                <div id="chart_div"></div>

                <ul class="flex border-b">
                    <li class="-mb-px mr-4">
                        <a class="bg-white inline-block border-b-2 border-green-base py-2 px-4 text-green-base font-semibold" href="#">W</a>
                    </li>
                    <li class="mr-4">
                        <a class="bg-white inline-block py-2 px-4 text-gray-900 hover:text-gray-800 font-semibold" href="#">M</a>
                    </li>
                    <li class="mr-4">
                        <a class="bg-white inline-block py-2 px-4 text-gray-900 hover:text-gray-800 font-semibold" href="#">S</a>
                    </li>
                    <li class="mr-4">
                        <a class="bg-white inline-block py-2 px-4 text-gray-900 hover:text-gray-800 font-semibold" href="#">Y</a>
                    </li>
                    <li class="mr-4">
                        <a class="bg-white inline-block py-2 px-4 text-gray-900 hover:text-gray-800 font-semibold" href="#">All</a>
                    </li>
                </ul>
        
                <!-- Customers List -->
                <div class="flex justify-between mt-12">
                <div class="flex justify-start">
                    <h3 class="text-lg text-gray-900">Customers</h3>
                    <a href="{{route('customers.create')}}" class="ml-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
                        <circle cx="12" cy="12" r="10" class="text-green-light fill-current"></circle>
                        <symbol id="add-customer" viewBox="0 0 25 25">
                        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z" class="text-green-base fill-current"/>
                        </symbol>
                        <use xlink:href="#add-customer" width="12" height="12" y="6" x="6" />
                    </svg>
                    </a>
                    </div>
                <div class="flex justify-end" x-data="{ sortOptions: false }">
                    <label for="sort_by" class="block text-xs font-medium leading-5 text-gray-700 mt-1">
                    Sort by:
                    </label>
                    <div class="relative inline-block text-left ml-2">
                        <div>
                            <span class="rounded-md shadow-sm">
                            <button x-on:click="sortOptions = !sortOptions" type="button" class="inline-flex justify-center w-full rounded-full border border-gray-300 px-4 py-1 rounded-full bg-green-base text-white text-sm leading-5 font-medium hover:bg-green-dark focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" aria-expanded="true">
                                Active
                                <svg class="-mr-1 ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            </span>
                        </div>
                        <div x-show="sortOptions" x-on:click.away="sortOptions = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                            <div class="rounded-md bg-white shadow-xs">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <button x-on:click="sortOptions = false" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                    Inactive
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="mt-6">
                @foreach ($customers as $customer)
                <a href="{{route('customers.show', $customer['id'])}}">
                    <div class="flex justify-between grid md:grid-cols-9 grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                        <div class="text-xs md:col-span-7 col-span-6">
                        {{{ $customer['name'] }}}
                        </div>
                        <div class="md:col-span-2 col-span-1 row-span-2">
                        <div class="bg-green-base text-white rounded-md py-1 px-2 text-center">
                            $ {{{ $customer['price'] }}}
                        </div>
                        </div>
                        <div class="text-xs text-gray-700 col-span-7">
                        {{{ $customer['kw'] }}}kW
                        </div>
                    </div>
                </a>
                @endforeach
                </div>
            </div>
        
            <!-- Personal Data -->
            <div class="px-4 py-5 sm:p-6 hidden md:block">
                <div class="flex justify-between">
                <div class="flex justify-start">
                    <a href="#" class="flex-shrink-0 group block focus:outline-none">
                    <div class="flex items-center">
                        <div>
                        <img class="inline-block h-16 w-16 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                        </div>
                        <div class="ml-3">
                        <p class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                            Tom Cook
                        </p>
                        <p class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
                            Zone XYZ Sales
                        </p>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="flex justify-end items-end">
                    <a href="#" class="ml-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" class="text-green-light fill-current"></circle>
                        <symbol id="logout" viewBox="0 0 25 25">
                        <path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z" class="text-green-base fill-current" />
                        </symbol>
                        <use xlink:href="#logout" width="12" height="12" y="6" x="7" />
                    </svg>
                    </a>
                    <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill-rule="evenodd" clip-rule="evenodd">
                        <circle cx="12" cy="12" r="10" class="text-green-light fill-current"></circle>
                        <symbol id="gear" viewBox="0 0 25 25">
                        <path d="M24 14.187v-4.374c-2.148-.766-2.726-.802-3.027-1.529-.303-.729.083-1.169 1.059-3.223l-3.093-3.093c-2.026.963-2.488 1.364-3.224 1.059-.727-.302-.768-.889-1.527-3.027h-4.375c-.764 2.144-.8 2.725-1.529 3.027-.752.313-1.203-.1-3.223-1.059l-3.093 3.093c.977 2.055 1.362 2.493 1.059 3.224-.302.727-.881.764-3.027 1.528v4.375c2.139.76 2.725.8 3.027 1.528.304.734-.081 1.167-1.059 3.223l3.093 3.093c1.999-.95 2.47-1.373 3.223-1.059.728.302.764.88 1.529 3.027h4.374c.758-2.131.799-2.723 1.537-3.031.745-.308 1.186.099 3.215 1.062l3.093-3.093c-.975-2.05-1.362-2.492-1.059-3.223.3-.726.88-.763 3.027-1.528zm-4.875.764c-.577 1.394-.068 2.458.488 3.578l-1.084 1.084c-1.093-.543-2.161-1.076-3.573-.49-1.396.581-1.79 1.693-2.188 2.877h-1.534c-.398-1.185-.791-2.297-2.183-2.875-1.419-.588-2.507-.045-3.579.488l-1.083-1.084c.557-1.118 1.066-2.18.487-3.58-.579-1.391-1.691-1.784-2.876-2.182v-1.533c1.185-.398 2.297-.791 2.875-2.184.578-1.394.068-2.459-.488-3.579l1.084-1.084c1.082.538 2.162 1.077 3.58.488 1.392-.577 1.785-1.69 2.183-2.875h1.534c.398 1.185.792 2.297 2.184 2.875 1.419.588 2.506.045 3.579-.488l1.084 1.084c-.556 1.121-1.065 2.187-.488 3.58.577 1.391 1.689 1.784 2.875 2.183v1.534c-1.188.398-2.302.791-2.877 2.183zm-7.125-5.951c1.654 0 3 1.346 3 3s-1.346 3-3 3-3-1.346-3-3 1.346-3 3-3zm0-2c-2.762 0-5 2.238-5 5s2.238 5 5 5 5-2.238 5-5-2.238-5-5-5z" width="12" height="12" class="text-green-base fill-current" />
                        </symbol>
                        <use xlink:href="#gear" width="12" height="12" y="6" x="6" />
                    </svg>
                    </a>
                </div>
                </div>
                <div class="mt-6">
                <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-4 text-xs text-gray-900">
                    DPS RATIO
                    </div>
                    <div class="col-span-4 text-xl font-bold text-gray-900">
                    1.67
                    </div>
                </div>
                <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-4 text-xs text-gray-900">
                    HPS RATIO
                    </div>
                    <div class="col-span-4 text-xl font-bold text-gray-900">
                    2.34
                    </div>
                </div>
                <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-4 text-xs text-gray-900">
                    SIT RATIO
                    </div>
                    <div class="col-span-4 text-xl font-bold text-gray-900">
                    55%
                    </div>
                </div>
                <div class="flex justify-between grid grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                    <div class="col-span-4 text-xs text-gray-900">
                    CLOSE RATIO
                    </div>
                    <div class="col-span-4 text-xl font-bold text-gray-900">
                    22%
                    </div>
                </div>

                <!-- Funnel Chart -->
                <div class="flex justify-between border-gray-200 border-2 m-1 p-2 rounded-lg">
                  <div id="barchart_values"></div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app.auth>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("current", {packages:["corechart", "bar"]});
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
      chartArea:{left:0,top:0,width:"99%",height:"100%"}
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
        chartArea:{left:0,top:0,width:"100%",height:"100%"},
        isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
</script>