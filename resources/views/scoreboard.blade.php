<x-app.auth :title="__('Scoreboard')">
  <div x-data="{openModal: false}">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between">
          <div class="flex justify-start">
            <h3 class="text-lg text-gray-900">Scoring</h3>
          </div>
          <div class="flex justify-end" x-data="{ showOptions: false }">
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
                        <x-table.th by="set_closes">
                            @lang('Set Closes')
                        </x-table.th>
                        <x-table.th by="office">
                            @lang('Office')
                        </x-table.th>
                      </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                      @foreach($data as $row)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{{ $row['id'] }}}
                                </span>
                              </x-table.td>
                              <x-table.td>{{{ $row['representative'] }}}</x-table.td>
                              <x-table.td>{{{ $row['set_closes'] }}}</x-table.td>
                              <x-table.td>{{{ $row['office'] }}}</x-table.td>
                          </x-table.tr>
                      @endforeach
                    </x-slot>
                  </x-table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6">
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
                        <x-table.th by="set_closes">
                            @lang('Set Closes')
                        </x-table.th>
                        <x-table.th by="office">
                            @lang('Office')
                        </x-table.th>
                      </x-table.th-tr>
                    </x-slot>
                    <x-slot name="body">
                      @foreach($data as $row)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{{ $row['id'] }}}
                                </span>
                              </x-table.td>
                              <x-table.td>{{{ $row['representative'] }}}</x-table.td>
                              <x-table.td>{{{ $row['set_closes'] }}}</x-table.td>
                              <x-table.td>{{{ $row['office'] }}}</x-table.td>
                          </x-table.tr>
                      @endforeach
                    </x-slot>
                  </x-table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6">
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
                      @foreach($data as $row)
                          <x-table.tr :loop="$loop" x-on:click="openModal = true" class="cursor-pointer">
                              <x-table.td>
                                  <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                                  {{{ $row['id'] }}}
                                </span>
                              </x-table.td>
                              <x-table.td>{{{ $row['representative'] }}}</x-table.td>
                              <x-table.td>{{{ $row['set_closes'] }}}</x-table.td>
                              <x-table.td>{{{ $row['office'] }}}</x-table.td>
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

      <div x-cloak x-show="openModal" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center z-20">
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
    
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
          <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
            <button type="button" x-on:click="openModal = false; setTimeout(() => open = true, 1000)" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150" aria-label="Close">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <x-profile.show-modal-profile-information/>

        </div>
      </div>
    </div>
  </div>
</x-app.auth>


