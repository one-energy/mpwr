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
    <div class="flex justify-end items-end">
        <a href="{{route('logout')}}" class="ml-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" class="text-green-light fill-current"></circle>
                <symbol id="logout" viewBox="0 0 25 25">
                <path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z" class="text-green-base fill-current" />
                </symbol>
                <use xlink:href="#logout" width="12" height="12" y="6" x="7" />
            </svg>
        </a>
        <a href="{{route('profile.show')}}">
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
        <div id="barchart_values" class="max-w-full min-w-full"></div>
    </div>
    </div>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("visualization", "1", {packages:["bar"]});
  google.charts.setOnLoadCallback(drawFunnelChart);

  function drawFunnelChart() {
      var data = google.visualization.arrayToDataTable([
        ["Type",   "#",  { role: "style" }],
        ["Doors",  2000, "color: #46A049"],
        ["Hours",  250,  "color: #7de8a6"],
        ["Sets",   125,  "color: #006400"],
        ["Sits",   85,   "color: #B5B5B5"],
        ["Closes", 22,   "color: #FF6E5D"]
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
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }
</script>