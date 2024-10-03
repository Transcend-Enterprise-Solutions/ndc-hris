<div class="w-full">
    
    <style>
        .scrollbar-thin1::-webkit-scrollbar {
                width: 5px;
            }

        .scrollbar-thin1::-webkit-scrollbar-thumb {
            background-color: #c0c0c04b;
        }

        .scrollbar-thin1::-webkit-scrollbar-track {
            background-color: #ffffff23;
        }

        @media (max-width: 1024px){
            .custom-d{
                display: block;
            }
        }

        @media (max-width: 768px){
            .m-scrollable{
                width: 100%;
                overflow-x: scroll;
            }
        }

        @media (min-width:1024px){
            .custom-p{
                padding-bottom: 14px !important;
            }
        }

        @-webkit-keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(0, 255, 42);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Service Records</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                
                <div class="w-full sm:w-1/3 sm:mr-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

            </div>


            <!-- Table -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                Name
                                            </th>
                                            @if ($filters['date_of_birth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Birth
                                                    Date</th>
                                            @endif
                                            @if ($filters['place_of_birth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Birth
                                                    Place</th>
                                            @endif
                                            @if ($filters['sex'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Sex
                                                </th>
                                            @endif
                                            @if ($filters['citizenship'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Citizenship</th>
                                            @endif
                                            @if ($filters['civil_status'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Civil
                                                    Status</th>
                                            @endif
                                            @if ($filters['height'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Height
                                                </th>
                                            @endif
                                            @if ($filters['weight'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Weight
                                                </th>
                                            @endif
                                            @if ($filters['blood_type'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Blood
                                                    Type</th>
                                            @endif
                                            @if ($filters['gsis'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    GSIS
                                                    ID No.</th>
                                            @endif
                                            @if ($filters['pagibig'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    PAGIBIG ID No.</th>
                                            @endif
                                            @if ($filters['philhealth'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    PhilHealth ID No.</th>
                                            @endif
                                            @if ($filters['sss'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    SSS
                                                    No.</th>
                                            @endif
                                            @if ($filters['tin'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    TIN
                                                    No.</th>
                                            @endif
                                            @if ($filters['agency_employee_no'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Agency
                                                    Employee No.</th>
                                            @endif
                                            @if ($filters['permanent_selectedProvince'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Permanent Address (Province)</th>
                                            @endif
                                            @if ($filters['permanent_selectedCity'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Permanent Address (City)</th>
                                            @endif
                                            @if ($filters['permanent_selectedBarangay'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Permanent Address (Barangay)</th>
                                            @endif
                                            @if ($filters['p_house_street'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Permanent Address (Street)</th>
                                            @endif
                                            @if ($filters['permanent_selectedZipcode'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Permanent Address (Zip Code)</th>
                                            @endif
                                            @if ($filters['residential_selectedProvince'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Residential Address (Province)</th>
                                            @endif
                                            @if ($filters['residential_selectedCity'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Residential Address (City)</th>
                                            @endif
                                            @if ($filters['residential_selectedBarangay'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Residential Address (Barangay)</th>
                                            @endif
                                            @if ($filters['r_house_street'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Residential Address (Street)</th>
                                            @endif
                                            @if ($filters['residential_selectedZipcode'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Residential Address (Zip Code)</th>
                                            @endif
                                            @if ($filters['active_status'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Active Status</th>
                                            @endif
                                            @if ($filters['appointment'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Nature of Appointment</th>
                                            @endif
                                            @if ($filters['date_hired'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Date Hired</th>
                                            @endif
                                            @if ($filters['years_in_gov_service'])
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                    Years in Gov Service</th>
                                            @endif
                                            <th
                                                class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($users as $user)
                                            <tr class="text-sm whitespace-nowrap">
                                                <td class="px-4 py-2 text-left">
                                                    {{ $user->name }}
                                                </td>
                                                @if ($filters['date_of_birth'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->date_of_birth }}</td>
                                                @endif
                                                @if ($filters['place_of_birth'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->place_of_birth }}</td>
                                                @endif
                                                @if ($filters['sex'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->sex }}
                                                    </td>
                                                @endif
                                                @if ($filters['citizenship'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->citizenship }}</td>
                                                @endif
                                                @if ($filters['civil_status'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->civil_status }}</td>
                                                @endif
                                                @if ($filters['height'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->height }}
                                                    </td>
                                                @endif
                                                @if ($filters['weight'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->weight }}
                                                    </td>
                                                @endif
                                                @if ($filters['blood_type'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->blood_type }}</td>
                                                @endif
                                                @if ($filters['gsis'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->gsis }}</td>
                                                @endif
                                                @if ($filters['pagibig'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->pagibig }}</td>
                                                @endif
                                                @if ($filters['philhealth'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->philhealth }}</td>
                                                @endif
                                                @if ($filters['sss'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->sss }}</td>
                                                @endif
                                                @if ($filters['tin'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->tin }}</td>
                                                @endif
                                                @if ($filters['agency_employee_no'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->agency_employee_no }}</td>
                                                @endif
                                                @if ($filters['permanent_selectedProvince'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->permanent_selectedProvince }}</td>
                                                @endif
                                                @if ($filters['permanent_selectedCity'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->permanent_selectedCity }}</td>
                                                @endif
                                                @if ($filters['permanent_selectedBarangay'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->permanent_selectedBarangay }}</td>
                                                @endif
                                                @if ($filters['p_house_street'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->p_house_street }}</td>
                                                @endif
                                                @if ($filters['permanent_selectedZipcode'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->permanent_selectedZipcode }}</td>
                                                @endif
                                                @if ($filters['residential_selectedProvince'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->residential_selectedProvince }}</td>
                                                @endif
                                                @if ($filters['residential_selectedCity'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->residential_selectedCity }}</td>
                                                @endif
                                                @if ($filters['residential_selectedBarangay'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->residential_selectedBarangay }}</td>
                                                @endif
                                                @if ($filters['r_house_street'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->r_house_street }}</td>
                                                @endif
                                                @if ($filters['residential_selectedZipcode'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->residential_selectedZipcode }}</td>
                                                @endif
                                                @if ($filters['active_status'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->active_status_label }}</td>
                                                @endif
                                                @if ($filters['appointment'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->appointment }}</td>
                                                @endif
                                                @if ($filters['date_hired'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->date_hired }}</td>
                                                @endif
                                                @if ($filters['years_in_gov_service'])
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->years_in_gov_service ?? 'N/A' }}</td>
                                                @endif
                                                <td
                                                    class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <button wire:click="showUser({{ $user->id }})"
                                                        class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-eye" title="Show Details"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
