<div>
  <div x-data="{openModal: false}">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between">
          <div class="flex justify-start">
            <h3 class="text-lg text-gray-900">Scoring</h3>
          </div>
          <div class="flex justify-end">
            <label for="show_option" class="block text-xs font-medium leading-5 text-gray-700 mt-2">
              Show:
            </label>
            <div class="relative inline-block text-left ml-2">
              <select id="show_option"
                      name="show_option"
                      onchange="this.form.submit()"
                      class="form-select block w-full transition duration-150 ease-in-out text-gray-500 text-lg py-1 rounded-lg">
                  @foreach($filterTypes as $type)
                      <option value="{{$type['index']}}"
                          @if(request('show_option') == $type['index']) selected @endif>
                          {{$type['value']}}
                      </option>
                  @endforeach
              </select>
            </div>
          </div>
        </div>
        
        <div class="mt-6">
          <span class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            Top 10 Hours
          </span>
          <div class="mt-6">
            <div class="flex flex-col">
              <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                  <x-table>
                    <x-slot name="header">
                      <x-table.th-tr>
                        <x-table.th by="rank">
                            @lang('Rank')
                        </x-table.th>
                        <x-table.th by="representative">
                            @lang('Representative')
                        </x-table.th>
                        <x-table.th by="hours">
                            @lang('Hours')
                        </x-table.th>
                        <x-table.th by="office">
                            @lang('Office')
                        </x-table.th>
                      </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                      @foreach($top10Hours as $user)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" wire:click="setUser({{ $user->id }})" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{ $loop->index+1 }}
                                </span>
                              </x-table.td>
                              <x-table.td>{{ $user->first_name }} {{ $user->last_name }}</x-table.td>
                              <x-table.td>{{ $user->hours }}</x-table.td>
                              <x-table.td>{{ $user->office }}</x-table.td>
                          </x-table.tr>
                      @endforeach
                    </x-slot>
                  </x-table>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="mt-9">
          <span class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            Top 10 Sets
          </span>
          <div class="mt-6">
            <div class="flex flex-col">
              <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                  <x-table>
                    <x-slot name="header">
                      <x-table.th-tr>
                        <x-table.th by="rank">
                            @lang('Rank')
                        </x-table.th>
                        <x-table.th by="representative">
                            @lang('Representative')
                        </x-table.th>
                        <x-table.th by="sets">
                            @lang('Sets')
                        </x-table.th>
                        <x-table.th by="office">
                            @lang('Office')
                        </x-table.th>
                      </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                      @foreach($top10Sets as $user)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" wire:click="setUser({{ $user->id }})" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{ $loop->index+1 }}
                                </span>
                              </x-table.td>
                              <x-table.td>{{ $user->first_name }} {{ $user->last_name }}</x-table.td>
                              <x-table.td>{{ $user->sets }}</x-table.td>
                              <x-table.td>{{ $user->office }}</x-table.td>
                          </x-table.tr>
                      @endforeach
                    </x-slot>
                  </x-table>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="mt-9">
          <span class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            Top 10 Set Closes
          </span>
          <div class="mt-6">
            <div class="flex flex-col">
              <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                  <x-table>
                    <x-slot name="header">
                      <x-table.th-tr>
                        <x-table.th by="rank">
                            @lang('Rank')
                        </x-table.th>
                        <x-table.th by="representative">
                            @lang('Representative')
                        </x-table.th>
                        <x-table.th by="set_closes">
                            @lang('Set Closes')
                        </x-table.th>
                        <x-table.th by="office">
                            @lang('Office')
                        </x-table.th>
                      </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                      @foreach($top10SetCloses as $user)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" wire:click="setUser({{ $user->id }})" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{ $loop->index+1 }}
                                </span>
                              </x-table.td>
                              <x-table.td>{{ $user->first_name }} {{ $user->last_name }}</x-table.td>
                              <x-table.td>{{ $user->set_closes }}</x-table.td>
                              <x-table.td>{{ $user->office }}</x-table.td>
                          </x-table.tr>
                      @endforeach
                    </x-slot>
                  </x-table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <svg class="absolute hidden left-1/2 top-1/2 w-20"
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
  
    @if($userId)
      <div x-cloak x-show="openModal" wire:loading.remove class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
          <div class="absolute top-0 right-0 pt-4 pr-4">
            <button type="button" x-on:click="openModal = false; setTimeout(() => open = true, 1000)" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between">
            <div class="flex justify-start">
                <div class="flex items-center">
                    <div>
                    <img class="inline-block h-16 w-16 rounded-full" src="{{ $user->photo_url }}" alt="" />
                    </div>
                    <div class="ml-3">
                    <p class="text-sm leading-5 font-medium text-gray-700 group-hover:text-gray-900">
                        {{ $first_name }} {{ $last_name }}
                    </p>
                    <p class="text-xs leading-4 font-medium text-gray-500 group-hover:text-gray-700 group-focus:underline transition ease-in-out duration-150">
                        {{ $office }}
                    </p>
                    </div>
                </div>
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
      </div>
    @endif
  </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    document.addEventListener("livewire:load", function(event) {
        window.livewire.hook('afterDomUpdate', () => {
          drawBarChart();
        });
    });

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawBarChart);
    
    function drawBarChart() {
        var doors  = @this.get('totalDoors');
        var hours  = @this.get('totalHours');
        var sets   = @this.get('totalSets');
        var sits   = @this.get('totalSits');
        var closes = @this.get('totalCloses');

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