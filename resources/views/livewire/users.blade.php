<x-app.auth :title="__('Users')">
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="md:flex">
                <div class="px-4 py-5 sm:px-6 md:w-1/3 overflow-hidden">
                    <div class="flex justify-start pb-4">
                        <h3 class="text-lg text-gray-900">Users</h3>
                    </div>

                    <div class="border-gray-200 border-2 p-4 rounded-lg">
                        <div class="flex justify-between">
                            <span>
                                Filters
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <symbol id="filter" viewBox="0 0 24 24">
                                    <path d="M19.479 2l-7.479 12.543v5.924l-1-.6v-5.324l-7.479-12.543h15.958zm3.521-2h-23l9 15.094v5.906l5 3v-8.906l9-15.094z" class="text-gray-700 fill-current"/>
                                </symbol>
                                <use xlink:href="#filter" width="15" height="15" y="4" x="4" />
                            </svg>
                        </div>
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
                        
                        <!-- Filter -->
                        <section class="mt-6">
                            <article>
                                <div class="border-b border-gray-200">
                                    <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                                        <span class="text-gray-70 font-thin text-sm">
                                            Region
                                        </span>
                                        <div class="ml-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" class="text-gray-300 fill-current"><path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg>
                                        </div>
                                    </header>
                                </div>
                            </article>
                            <article>
                                <div class="border-b bg-grey-lightest border-gray-200">
                                    <header class="flex justify-between items-center py-2 cursor-pointer select-none">
                                        <span class="text-gray-700 font-thin text-sm">
                                            Member Team
                                        </span>
                                        <div class="flex">
                                            <div class="rounded-full border border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                                                1
                                            </div>
                                            <div class="ml-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" class="text-gray-300 fill-current"><path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg>
                                            </div>
                                        </div>
                                    </header>
                                    <div>
                                        <div class="pl-2 pb-5 text-sm text-grey-darkest">
                                            <ul class="pl-2">
                                                <li class="pb-2">
                                                    Closer
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <div class="flex justify-between">
                                <div class="flex mt-12">
                                    <span class="text-sm">
                                        Active Filters
                                    </span>
                                    <div class="ml-6 mt-1 rounded-full border border border-gray-200 w-4 h-4 flex items-center justify-center bg-gray-200 text-gray-700 text-xs">
                                        1
                                    </div>
                                </div>
                                <div class="mt-12">
                                    <a href="#" class="text-xs text-gray-600">
                                        Clear Filters
                                    </a>
                                </div>
                            </div>
                            <div class="mt-2 border-t border-gray-200">
                                <div class="mt-2">
                                    <span class="rounded-full text-xs border border-gray-700 px-2">
                                        Closer
                                    </span>
                                </div>
                            </div>
                        </section>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex w-full justify-center py-2 px-4 border-2 border-gray-700 text-sm leading-5 font-medium rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                                Apply Filters
                            </button>
                        </div> 
                    </div>
                </div>

                <div class="px-4 py-5 sm:px-6 w-2/3">
                    <div>
                        <x-button :href="route('castle.users.create')" color="indigo" class="mt-4 sm:mt-0">
                            @lang('Create a new User')
                        </x-button>
                        
                        <div class="mt-3">
                            <div class="flex flex-col">
                                <div class="">
                                    <div class="align-middle inline-block min-w-full">
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
            </div>
        </div>
    </div>
</x-app.auth>
