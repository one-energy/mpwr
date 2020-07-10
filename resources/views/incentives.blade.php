<x-app.auth :title="__('Incentives')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-start">
          <h3 class="text-lg text-gray-900">Incentives</h3>
        </div>

        <div class="mt-3">
          <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
              <div class="align-middle inline-block min-w-full overflow-hidden">
                <table class="min-w-full">
                  <thead>
                    <tr class="sm:border-gray-200 border-b-2">
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        # of Installs
                      </th>
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        Incentive
                      </th>
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        % Achieved (Installs)
                      </th>
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        Needed (Installs)
                      </th>
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        % Achieved (kW's)
                      </th>
                      <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                        Needed (kW's)
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data as $row)
                      <tr class="md:border-gray-200 md:border-2 md:rounded-lg">
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                          <span class="px-2 inline-flex rounded-full bg-green-base text-white">
                            {{{ $row['number_installs'] }}}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                          {{{ $row['incentive'] }}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                          {{{ $row['installs_achieved'] }}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                          {{{ $row['installs_needed'] }}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                          {{{ $row['kw_achieved'] }}}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                          {{{ $row['kw_needed'] }}}
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
</x-app.auth>