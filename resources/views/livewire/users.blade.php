<x-app.auth :title="__('Users')">
    <div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex">

                <div class="px-4 py-5 sm:px-6 w-1/3">

                    <div class="flex justify-start">
                        <h3 class="text-lg text-gray-900">Users</h3>
                    </div>

                    <div class="border-gray-200 border-2 p-4 rounded-lg mt-7">
                        <span>
                            Filters
                        </span>
                        <div class="pt-2 relative mx-auto text-gray-600">
                            <input class="border-2 border-gray-300 bg-white h-10 w-full px-5 pr-16 rounded-lg text-sm focus:outline-none"
                              type="search" name="search" placeholder="Search by Keyword">
                            <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                              <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                width="512px" height="512px">
                                <path
                                  d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                              </svg>
                            </button>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="inline-flex w-full justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="px-4 py-5 sm:px-6 w-2/3">
                <div class="flex justify-between border-gray-200 p-4 mt-1">
                            <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        Office
                                    </th>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        Pay
                                    </th>
                                    <th class="px-6 py-3 uppercase text-left text-xs leading-4 font-medium text-gray-900 uppercase tracking-wider">
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $row)
                                <tr class="border-gray-200 border-2 rounded-lg">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                    {{{ $row['first_name']. ' ' . $row['last_name'] }}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                                    {{{ $row['email'] }}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                                    {{{ $row['role'] }}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                                    {{{ $row['office'] }}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                                    {{{ $row['pay'] }}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-800">
                                        <x-link class="text-sm" :href="route('castle.users.edit', $row['id'])">Edit</x-link>
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
</x-app.auth>
