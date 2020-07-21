<x-app.auth :title="__('Scoreboard')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between">
          <div class="flex justify-start">
            <h3 class="text-lg text-gray-900">Scoring</h3>
          </div>
          <div class="flex justify-end" x-data="{ showOptions: false }">
            <label for="show_option" class="block text-xs font-medium leading-5 text-gray-700 mt-1">
              Show:
            </label>
            <div class="relative inline-block text-left ml-2">
              <div>
                <span class="rounded-md shadow-sm">
                  <button x-on:click="showOptions = !showOptions" type="button" class="inline-flex justify-center w-full rounded-full border border-gray-300 px-4 py-1 rounded-full bg-green-base text-white text-sm leading-5 font-medium hover:bg-green-dark focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    Leaderboards
                    <svg class="-mr-1 ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </span>
              </div>
              <div x-show="showOptions" x-on:click.away="showOptions = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                <div class="rounded-md bg-white shadow-xs">
                  <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                      <button x-on:click="showOptions = false" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                        Records
                      </button>
                  </div>
                </div>
              </div>
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
                        <tr class="md:border-gray-200 md:border-2 rounded-lg">
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
                        <tr class="md:border-gray-200 md:border-2 rounded-lg">
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
                        <tr class="md:border-gray-200 md:border-2 rounded-lg">
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