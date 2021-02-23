<x-app.auth :title="__('New User')">
    <div>
        <div class="max-w-6xl mx-auto py-5 sm:px-6 lg:px-8">
            <x-link :href="route('castle.users.index')" color="gray"
                    class="inline-flex items-center border-b-2 border-green-base hover:border-green-500 text-sm font-medium leading-5">
                <x-svg.chevron-left class="w-6 -ml-2"/> @lang('User Info')
            </x-link>
        </div>

        @if ($message = session('message'))
            <x-alert class="mb-4" :title="__('Success')" :description="$message"></x-alert>
        @endif


        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-form :route="route('castle.users.store')">
                <div x-data="register()" x-init="$watch('selectedDepartment', (department) => {
                            const url = '{{ route('getOffices', ':department') }}'.replace(':department', department);

                            fetch(url, {
                                method: 'post',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            }).then(res => res.json()).then((officesData) => {
                                offices = officesData
                            })
                        });

                        $watch('selectedRole', (role) => {
                            const url = '{{ route('getRatesPerRole', ':role') }}'.replace(':role', role);

                            fetch(url, {
                                method: 'post',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            }).then(res => res.json()).then((ratesData) => {
                                rate = ratesData.rate
                            })
                        });">
                    <div class="mt-6 grid grid-cols-2 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('First Name')" name="first_name"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('Last Name')" name="last_name"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input :label="__('Email')" name="email"/>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-select x-model="selectedRole" label="Role" name="role">
                                <template x-if="roles" x-for="role in roles" :key="role.name">
                                    <option :value="role.name" x-text="role.title"></option>
                                </template>
                            </x-select>
                        </div>
                        @if(user()->role != "Admin" && user()->role != "Owner")
                            <div class="md:col-span-3 col-span-2 hidden">
                                <x-select x-model="selectedDepartment" label="Department" name="department_id">
                                    <template x-if="departments" x-for="department in departments" :key="department.id">
                                        <option :value="department.id" x-text="department.name"></option>
                                    </template>
                                </x-select>
                            </div>
                        @else
                            <div class="md:col-span-3 col-span-2">
                                <x-select x-model="selectedDepartment" label="Department" name="department_id">
                                    <option value="">None</option>
                                    <template x-if="departments" x-for="department in departments" :key="department.id">
                                        <option :value="department.id" x-text="department.name"></option>
                                    </template>
                                </x-select>
                            </div>
                        @endif

                        <div class="md:col-span-3 col-span-2">
                            <x-select x-model="selectedOffice" label="Office" name="office_id">
                                @if(user()->role == "Admin" || user()->role == "Owner")
                                    <template
                                        x-if="selectedRole != 'Setter' && selectedRole != 'Sales Rep' && selectedRole != 'Office Manager'">
                                        <option value="">None</option>
                                    </template>
                                @endif
                                <template x-if="offices" x-for="office in offices" :key="office.id">
                                    <option :value="office.id" x-text="office.name"></option>
                                </template>
                            </x-select>
                        </div>

                        <div class="md:col-span-3 col-span-2">
                            <x-input-currency  :label="__('Pay Rate ($/W)')" name="pay" x-model="rate"/>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-2 flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Send Welcome Email
                        </button>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <a href="{{route('castle.users.index')}}"
                           class="py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-gray-800 hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </span>
                </div>

            </x-form>
        </div>
    </div>

    @push('scripts')
        <script>
            function register() {
                return {
                    selectedDepartment: "{{ user()->department_id }}",
                    selectedRole: null,
                    departments: @json($departments),
                    offices: null,
                    roles: @json($roles),
                    rate: null,
                    selectedOffice: null,
                    token: document.head.querySelector('meta[name=csrf-token]').content
                }
            }
        </script>
    @endpush
</x-app.auth>
