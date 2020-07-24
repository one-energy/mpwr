<x-app.auth :title="__('Dashboard')">
    <div>
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="md:flex">
            <div class="px-4 py-5 sm:px-6 sm:w-full md:w-2/3 overflow-y-auto">
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
                <div id="chart_div" class="max-w-full"></div>

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
                <form  action="{{ route('home') }}">
                    <div class="flex justify-end" x-data="{ sortOptions: false }">
                        <label for="sort_by" class="block text-xs font-medium leading-5 text-gray-700 mt-1">
                        Sort by:
                        </label>
                        <div class="relative inline-block text-left ml-2">
                            <select id="sort_by"
                                    name="sort_by"
                                    onchange="this.form.submit()"
                                    class="form-select block w-full transition duration-150 ease-in-out text-gray-500 text-lg py-1 rounded-lg">
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
                        <div class="flex justify-between grid md:grid-cols-9 grid-cols-4 row-gap-1 col-gap-4 border-gray-200 border-2 m-1 p-2 rounded-lg">
                            <div class="md:col-span-7 col-span-6">
                                {{$customer->first_name }} {{ $customer->last_name }}
                            </div>
                            <div class="md:col-span-2 col-span-1 row-span-2">
                            <div class="bg-green-base text-white rounded-md py-1 text-center">
                                $ {{ $customer->comission }}
                            </div>
                            </div>
                            <div class="text-xs text-gray-600 col-span-7">
                                {{ $customer->gross_ppw }}kW
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="h-96 ">
                        <div class="flex align-middle justify-center">
                            <div class="text-gray-700 text-sm text-center">
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
                @include('components\profile\show-profile-information')
            </div>
            </div>
        </div>
    </div>
</x-app.auth>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load("visualization", "1", {packages:["corechart"]});
  google.charts.setOnLoadCallback(drawAreaChart);

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

  $(window).resize(function(){
    drawAreaChart();
});
</script>