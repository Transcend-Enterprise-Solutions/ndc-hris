<div class="p-10 flex justify-center w-full">
    <div class="w-full">
        <div x-data="{ dropdownOpen: false, filters: { name: true, email: true } }"
            class="flex items-center justify-center p-4">
            <!-- Filter Dropdown -->
            <div class="relative inline-block text-left">
                <button @click="dropdownOpen = !dropdownOpen"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    type="button">
                    Filter by category
                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    class="z-10 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center">
                            <input id="name" type="checkbox" x-model="filters.name"
                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                            <label for="name"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Name</label>
                        </li>
                        <li class="flex items-center">
                            <input id="email" type="checkbox" x-model="filters.email"
                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                            <label for="email"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Email</label>
                        </li>
                        <!-- Add more filters as needed -->
                    </ul>
                </div>
            </div>

            <!-- Generate Button -->
            <button
                class="ml-4 text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                @click="Livewire.emit('exportUsers', filters)">Generate</button>
        </div>
        <div class="inline-block w-full">
            <div class="overflow-hidden border rounded-lg border-neutral-500 dark:border-neutral-200">
                <table class="divide-y divide-neutral-500 dark:divide-neutral-200 w-full">
                    <thead class="text-neutral-500 dark:text-neutral-200">
                        <tr class="text-neutral-800 dark:text-neutral-200">
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/5">Name</th>
                            {{-- <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Sex</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/5">Citizenship</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Civil Status</th>
                            <th class="px-5 py-3 text-sm font-medium text-left uppercase w-1/10">Mobile Number</th> --}}
                            <th class="px-5 py-3 text-sm font-medium text-right uppercase w-1/10">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-500">
                        @foreach($users as $user)
                        <tr class="text-neutral-800 dark:text-neutral-200">
                            <td class="px-5 py-4 text-sm font-medium whitespace-nowrap w-1/5">{{ $user->name }}</td>
                            {{-- <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->sex }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/5">{{ $user->citizenship }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->civil_status }}</td>
                            <td class="px-5 py-4 text-sm whitespace-nowrap w-1/10">{{ $user->mobile_number }}</td> --}}
                            <td class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap w-1/10">
                                <button wire:click="showUser({{ $user->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-neutral-800 dark:text-neutral-200 transition-colors duration-200 border rounded-lg border-neutral-500 dark:border-neutral-200 hover:bg-slate-900 dark:hover:bg-slate-100 hover:text-slate-100 dark:hover:text-slate-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Show</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-neutral-500 dark:text-neutral-200">
                        <tr>
                            <td colspan="6" class="p-0">
                                <div class="flex items-center justify-between h-16 px-3 border-t border-neutral-200">
                                    <p class="pl-2 text-sm text-neutral-800 dark:text-neutral-200">
                                        Showing <span class="font-medium">{{ $users->firstItem() }}</span> to
                                        <span class="font-medium">{{ $users->lastItem() }}</span> of
                                        <span class="font-medium">{{ $users->total() }}</span> results
                                    </p>
                                    <nav x-data="{}">
                                        <ul
                                            class="flex items-center text-sm leading-tight bg-white border divide-x rounded h-9 text-neutral-500 divide-neutral-200 border-neutral-200">
                                            {{ $users->links() }}
                                        </ul>
                                    </nav>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($selectedUser)
        <!-- Modal Popup -->
        <div class="fixed z-50 inset-0 overflow-y-auto" x-show="showModal" x-cloak>
            <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-4/5">
                    <!-- Modal content -->
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $selectedUserData->surname }}'s Profile
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Full name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUser->name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Birth Date
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->date_of_birth }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Sex at Birth
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->sex }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Citizenship
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->citizenship }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Email address
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUser->email }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Phone number
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->mobile_number }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Civil Status
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->civil_status }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Blood Type
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->blood_type }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Height
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->height }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Weight
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->weight }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        GSIS ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->gsis }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        PAGIBIG ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->pagibig }}
                                    </dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        PhilHealth ID No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->philhealth }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        SSS No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->sss }}
                                    </dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        TIN No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->tin }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Agency Employee No.
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->agency_employee_no }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Spouse Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->spouse_name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Spouse Birth Date
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->spouse_birth_date }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Children's Names
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $childrenNames ?? 'No children recorded' }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Children's Birth Dates
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $childrenBirthDates ?? 'No birth dates recorded' }}
                                    </dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 divide-x">
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Fathers Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->fathers_name }}
                                    </dd>
                                </div>
                                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">
                                        Mothers Maiden Name
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ $selectedUserData->mothers_maiden_name }}
                                    </dd>
                                </div>
                            </div>

                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Permanent Address
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $p_full_address }}
                                </dd>
                            </div>

                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Residential Address
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $r_full_address }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="w-full text-black text-center border-t border-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Educational Background
                            </h3>
                        </div>
                        <div class="border-t border-gray-200 px-4 sm:p-0">
                            <dl class="sm:divide-y sm:divide-gray-200">
                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Name of School
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->name_of_school }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-xs font-medium text-gray-500">
                                            Highest Educational Attainment
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->educ_background }}
                                        </dd>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Degree
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->degree }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Year Graduated
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->year_graduated }}
                                        </dd>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 divide-x">
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Period of Attendance (Start)
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->period_start_date }}
                                        </dd>
                                    </div>
                                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Period of Attendance (End)
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $selectedUserData->period_end_date }}
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6">
                        <button wire:click="closeUserProfile" class="text-blue-600 hover:text-blue-700">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>