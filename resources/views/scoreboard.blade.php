<x-app.auth :title="__('Scoreboard')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between">
          <div class="flex justify-start">
            <h3 class="text-lg text-gray-900">Scoring</h3>
          </div>
          <div class="flex justify-end">
            <label for="show_option" class="block text-xs font-medium leading-5 text-gray-700 mt-1">
              Show:
            </label>
            <div class="ml-2">
              <select form="showOption" name="show_option" id="show_option" class="form-select block w-full pl-2 pr-10 py-1 text-sm leading-6 rounded-full bg-green-base text-white focus:outline-none focus:shadow-outline-green focus:border-green-300 sm:text-sm sm:leading-5 " onchange="this.form.submit()">
                @foreach($showOptions as $showOption)
                    <option {{request()->get('show_option') == $showOption ? 'selected' : '' }} value="{{$showOption}}">{{$showOption}}</option>
                @endforeach
            </select>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <span class="inline-flex items-center pt-1 border-b-2 border-green-base text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-green-base transition duration-150 ease-in-out">
            Top 10 Hours
          </span>
          <div class="mt-6">
            <div class="flex flex-col">
              <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                  <table class="min-w-full">
                    <thead>
                      <tr>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Rank
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Representative
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Set Closes
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Office
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data as $row)
                        <tr class="border-gray-200 border-2 rounded-lg">
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['office'] }}}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
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
                  <table class="min-w-full">
                    <thead>
                      <tr>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Rank
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Representative
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Set Closes
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Office
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data as $row)
                        <tr class="border-gray-200 border-2 rounded-lg">
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['office'] }}}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
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
                  <table class="min-w-full">
                    <thead>
                      <tr>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Rank
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Representative
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Set Closes
                        </th>
                        <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                          Office
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data as $row)
                        <tr class="border-gray-200 border-2 rounded-lg">
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                            {{{ $row['office'] }}}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</x-app.auth>