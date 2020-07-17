<x-app.auth :title="__('Scoreboard')">
  <div>
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
                      <tr class="sm:border-gray-200 border-b-2">
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
                        <tr>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 md:border-b md:border-gray-200">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
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
                      <tr class="sm:border-gray-200 border-b-2">
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
                        <tr>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 md:border-b md:border-gray-200">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
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
                      <tr class="sm:border-gray-200 border-b-2">
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
                        <tr>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 md:border-b md:border-gray-200">
                            <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                              {{{ $row['id'] }}}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['representative'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
                            {{{ $row['set_closes'] }}}
                          </td>
                          <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800 md:border-b md:border-gray-200">
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

        </div>

      </div>
    </div>
  </div>
</x-app.auth>