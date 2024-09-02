<div class="w-full"
x-data="{ 
    selectedTab: 'settings',
}" 
x-cloak>

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
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Organization Management</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'org'">
                    <label for="search2" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search2" wire:model.live="search2"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Search name/id/position/office/divisions">
                </div>

                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'role'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'pos'">
                    <label for="search3" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search3" wire:model.live="search3"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'org'">
              
                    <div class="w-full sm:w-auto relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-2 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Filter Status
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>

                        <div x-show="open"
                            class="absolute top-12 z-20 p-3 border border-gray-400 bg-white rounded-lg 
                            shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1" style="width: 130px">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Select Status</h6>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-center" wire:click='toggleAllStats'>
                                    <label for="allCol" class="px-2 text-gray-900 bg-slate-200 rounded-sm">
                                    {{ $allStat ? 'Unselect' : 'Select' }} All
                                    </label>
                                </li>
                                <li class="flex items-center">
                                    <input id="allCol" type="checkbox" wire:model.live="status.active"
                                        class="h-4 w-4">
                                    <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Active</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="allCol" type="checkbox" wire:model.live="status.inactive"
                                        class="h-4 w-4">
                                    <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Inactive</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="allCol" type="checkbox" wire:model.live="status.resigned"
                                        class="h-4 w-4">
                                    <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Resigned</label>
                                </li>
                                <li class="flex items-center">
                                    <input id="allCol" type="checkbox" wire:model.live="status.retired"
                                        class="h-4 w-4">
                                    <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Retired</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'role'">

                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddRole" 
                            class="mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add Role
                        </button>
                    </div>

                    <!-- Export to Excel -->
                    {{-- <div class="relative inline-block text-left">
                        <button wire:click="exportExcel"
                            class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button"  aria-describedby="excelExport">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                        </button>
                        <div id="excelExport" class="absolute -top-5 left-1/2 -translate-x-1/2 z-10 whitespace-nowrap rounded bg-gray-600 px-2 py-1 text-center text-sm text-white opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:text-black" role="tooltip">Export Roles</div>
                    </div> --}}

                </div>
                
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'pos'">      

                        <!-- Export to Excel -->
                        {{-- <div class="relative inline-block text-left">
                            <button wire:click="exportExcel"
                                class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                                text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                                rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button"  aria-describedby="excelExport">
                                <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                            </button>
                            <div id="excelExport" class="absolute -top-5 left-1/2 -translate-x-1/2 z-10 whitespace-nowrap rounded bg-gray-600 px-2 py-1 text-center text-sm text-white opacity-0 transition-all ease-out peer-hover:opacity-100 peer-focus:opacity-100 dark:text-black" role="tooltip">Export Roles</div>
                        </div> --}} 
                </div>
            </div>

            <!-- Table -->
            <div class="w-full">
                <div class="flex flex-col">
                    <div class="flex gap-2 overflow-x-auto -mb-2">
                        <button @click="selectedTab = 'org'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'org', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'org' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                            Organization
                        </button>
                        <button @click="selectedTab = 'role'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'role', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'role' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                            Admin Role
                        </button>
                        <button @click="selectedTab = 'pos'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'pos', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'pos' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                            Employee Settings
                        </button>
                        <button @click="selectedTab = 'settings'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'settings', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'settings' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                            HR Settings
                        </button>
                        <button @click="selectedTab = 'sgstep'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'sgstep', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'sgstep' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                            SG/STEP 
                        </button>
                    </div>
                    <div class="-my-2 overflow-x-auto">
                        <div class="inline-block w-full py-2 align-middle">
                            <div>
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div x-show="selectedTab === 'org'">
                                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                            @forelse ($organizations as $division => $users)
                                                <div class="block lg:flex w-full bg-white dark:bg-gray-600 mb-5" style="max-height: 300px">
                                                    <div class="pb-4 flex flex-col gap-2 sm:w-1/5 bg-gray-50 dark:bg-gray-800 relative">
                                                        <h2 class="text-wrap text-sm pb-2 h-min px-4 pt-2 font-bold dark:text-gray-300">
                                                            <i class="bi bi-building mr-2 text-emerald-500 dark:text-emerald-300"></i>
                                                            {{ $division }}
                                                        </h2>
                                                        <div class="flex flex-col justify-center items-center" style="height: 120px">
                                                            <p class="text-5xl font-bold">{{ count($users) }}</p>
                                                            <p class="text-xs">Number of Employees</p>
                                                        </div>
                                                        <div class="flex justify-center items-center absolute bottom-2 w-full">
                                                            <button wire:click="exportEmployees('{{ $division }}')" 
                                                                class="peer inline-flex items-center justify-center px-2
                                                                text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                                title="Export List">
                                                                <img class="flex dark:hidden" src="/images/icons8-xls-export-dark.png" width="18" alt="" wire:loading.remove wire:target="exportEmployees('{{ $division }}')">
                                                                <img class="hidden dark:block" src="/images/icons8-xls-export-light.png" width="18" alt="" wire:loading.remove wire:target="exportEmployees('{{ $division }}')">
                                                                <div wire:loading wire:target="exportEmployees('{{ $division }}')" style="margin-left: 5px">
                                                                    <div class="spinner-border small text-primary" role="status">
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="pb-2 px-2 sm:w-4/5 flex flex-col">
                                                        <div class="overflow-x-auto overflow-y-hidden">
                                                            <table class="w-full">
                                                                <thead class="bg-white dark:bg-gray-600 text-gray-300 dark:text-gray-400">
                                                                    <tr class="whitespace-nowrap border-b border-gray-100 dark:border-gray-500">
                                                                        <th width="30%" scope="col" class="px-2 py-3 text-xs font-medium text-left uppercase sticky top-0 bg-white dark:bg-gray-600">
                                                                            Name
                                                                        </th>
                                                                        <th width="15%" scope="col" class="px-2 py-3 text-xs font-medium text-center uppercase sticky top-0 bg-white dark:bg-gray-600">
                                                                            Emp Number
                                                                        </th>
                                                                        <th width="30%" scope="col" class="px-2 py-3 text-xs font-medium text-center uppercase sticky top-0 bg-white dark:bg-gray-600">
                                                                            Position
                                                                        </th>
                                                                        <th width="20%" scope="col" class="px-2 py-3 text-xs font-medium text-left uppercase sticky top-0 bg-white dark:bg-gray-600">
                                                                            Appointment
                                                                        </th>
                                                                        <th width="5%" scope="col" class="px-2 py-3 text-xs font-medium text-center uppercase sticky top-0 bg-white dark:bg-gray-600">
                                                                            Status
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <div class="overflow-y-auto flex-grow scrollbar-thin1" style="max-height: calc(300px - 3rem);">
                                                            <table class="w-full">
                                                                <tbody>
                                                                    @foreach ($users as $user)
                                                                        @if (is_object($user))
                                                                            <tr class="text-neutral-800 dark:text-neutral-200 border-b border-gray-100 dark:border-gray-500">
                                                                                <td width="30%" class="px-2 py-2 text-left text-xs text-nowrap">
                                                                                    {{ $user->name ?? 'N/A' }}
                                                                                </td>
                                                                                <td width="15%" class="px-2 py-2 text-center text-xs text-nowrap">
                                                                                    {{ $user->emp_code }}
                                                                                </td>
                                                                                <td width="30%" class="px-2 py-2 text-center text-xs text-nowrap">
                                                                                    {{ $user->position ?? 'N/A' }}
                                                                                </td>
                                                                                <td width="20%" class="px-2 py-2 text-left text-xs text-nowrap uppercase">
                                                                                    @if($user->appointment != "cos" && $user->appointment != "ct")
                                                                                        @php
                                                                                            $appointment = explode(',', $user->appointment);
                                                                                        @endphp
                                                                                        @if($appointment[0] == 'pa')
                                                                                           Presidential Appointee
                                                                                        @else
                                                                                            Plantilla
                                                                                        @endif
                                                                                    @else
                                                                                        @if($user->appointment == "ct")
                                                                                            Co-Terminus
                                                                                        @else
                                                                                            {{ $user->appointment }}
                                                                                        @endif
                                                                                    @endif
                                                                                </td>
                                                                                <td width="5%" class="px-2 py-2 text-center text-xs text-nowrap">
                                                                                    <span title="
                                                                                        {{ $user->active_status == 0 ? 'Status: Inactive' : '' }} 
                                                                                        {{ $user->active_status == 1 ? 'Status: Active' : '' }} 
                                                                                        {{ $user->active_status == 2 ? 'Status: Resigned' : '' }} 
                                                                                        {{ $user->active_status == 3 ? 'Status: Retired' : '' }}"
                                                                                        class="inline-block px-3 py-1 text-xs font-semibold 
                                                                                        {{ $user->active_status == 0 ? 'text-red-400' : '' }} 
                                                                                        {{ $user->active_status == 1 ? 'text-green-400' : '' }} 
                                                                                        {{ $user->active_status == 2 ? 'text-yellow-400' : '' }} 
                                                                                        {{ $user->active_status == 3 ? 'text-purple-400' : '' }}">
                                                                                        ⦿
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                    No records found!
                                                </div>
                                            @endforelse
                                        </div>
                                     </div>
                                    <div x-show="selectedTab === 'role'">
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-full">
                                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                    <tr class="whitespace-nowrap">
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                            Admin Role
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                            Name
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Employee Number
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Office/Division
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Position
                                                        </th>
                                                        <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                    @foreach ($admins as $admin)
                                                        <tr class="text-neutral-800 dark:text-neutral-200">
                                                            <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                                @if ($admin->user_role === 'sa')
                                                                    Super Admin
                                                                @elseif($admin->user_role === 'hr')
                                                                    HR 
                                                                @elseif($admin->user_role === 'sv')
                                                                    Supervisor
                                                                @elseif($admin->user_role === 'pa')
                                                                    Payroll
                                                                @endif
                                                            </td>
                                                            <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                                {{ $admin->name }}
                                                            </td>
                                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                @php
                                                                    $empCode = explode('-', $admin->emp_code);
                                                                @endphp
                                                                {{ $empCode[1] }}
                                                            </td>
                                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                {{ $admin->office_division }}
                                                            </td>
                                                            <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                {{ $admin->position }}
                                                            </td>
                                                            <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                                <div class="relative">
                                                                    @if($admin->position != "Super Admin")
                                                                        <button wire:click="toggleEditRole({{ $admin->user_id }})" 
                                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                            focus:outline-none" title="Edit">
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </button>
                                                                        <button wire:click="toggleDelete({{ $admin->user_id }}, 'role')" 
                                                                            class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                                            dark:hover:text-red-900" title="Delete">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if ($admins->isEmpty())
                                                <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                    No records!
                                                </div> 
                                            @endif
                                        </div>
                                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                            {{ $admins->links() }}
                                        </div>
                                     </div>
                                    <div x-show="selectedTab === 'pos'">
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-full">
                                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                    <tr class="whitespace-nowrap">
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                            Name
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Employee Number
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Office/Division
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Position
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Appointment
                                                        </th>
                                                        <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                            Status
                                                        </th>
                                                        <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                    @foreach ($empPos as $pos)
                                                        @if($pos->position != "Super Admin")
                                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                                <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                                    {{ $pos->name }}
                                                                </td>
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                    {{ $pos->emp_code }}
                                                                </td>
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                    {{ $pos->office_division }}
                                                                </td>
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                    {{ $pos->position }}
                                                                </td>
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap uppercase">
                                                                    @if($pos->appointment != "cos" && $pos->appointment != "ct")
                                                                        @php
                                                                            $appointment = explode(',', $pos->appointment);
                                                                        @endphp
                                                                        @if($appointment[0] == 'pa')
                                                                            Presidential Appointee
                                                                        @else
                                                                            Plantilla
                                                                        @endif
                                                                    @else
                                                                        @if($pos->appointment == "ct")
                                                                            Co-Terminus
                                                                        @else
                                                                            {{ $pos->appointment }}
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                                    <span
                                                                        class="inline-block px-3 py-1 text-sm font-semibold 
                                                                        {{ $pos->active_status == 0 ? 'text-red-800 bg-red-200' : '' }} 
                                                                        {{ $pos->active_status == 1 ? 'text-green-800 bg-green-200' : '' }} 
                                                                        {{ $pos->active_status == 2 ? 'text-yellow-800 bg-yellow-200' : '' }} 
                                                                        {{ $pos->active_status == 3 ? 'text-purple-800 bg-purple-200' : '' }} 
                                                                         rounded-full">
                                                                        @if($pos->active_status == 0)
                                                                            Inactive
                                                                        @elseif($pos->active_status == 1)
                                                                            Active
                                                                        @elseif($pos->active_status == 2)
                                                                            Resigned
                                                                        @elseif($pos->active_status == 3)
                                                                            Retired
                                                                        @endif
                                                                    </span>
                                                                </td>
                                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                                    <div class="relative">
                                                                        <button wire:click="toggleEditPosition({{ $pos->id }})" 
                                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                            -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                            focus:outline-none" title="Edit">
                                                                            <i class="fas fa-pencil-alt"></i>
                                                                        </button>
                                                                        <button wire:click="toggleDelete({{ $pos->id }}, 'role')" 
                                                                            class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                                            dark:hover:text-red-900" title="Delete">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if ($empPos->isEmpty())
                                                <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                    No records!
                                                </div> 
                                            @endif
                                        </div>
                                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                            {{ $empPos->links() }}
                                        </div>
                                     </div>

                                    <div x-show="selectedTab === 'settings'">
                                        <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                            <table class="w-full min-w-full">
                                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl" style="height: 20px">
                                                    <tr class="whitespace-nowrap">
                                                        <td>
                                                            <div class="flex flex-col md:flex-col lg:flex-row lg:justify-between items-center w-full mb-2">
                                                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Office / Division <span class="mx-2">|</span> <span>Units</span> <span class="mx-2">|</span> <span>Positions</span></h3>
                                                                <div>
                                                                    <button wire:click="toggleAddSettings('office/division')" 
                                                                        class="peer inline-flex items-center justify-center px-4 py-2 
                                                                        text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                        focus:outline-none" title="Add">
                                                                        <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>


                                            @foreach ($officeDivisions as $officeDivision)

                                                <!-- Office/Division Header -->
                                                <div class="flex justify-between items-center w-full py-1.5 bg-gray-50 dark:bg-gray-800 px-4">
                                                    <div class="flex items-end">
                                                        <i class="bi bi-building mr-2 text-emerald-500 dark:text-emerald-300 mr-2"></i>
                                                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-300">{{ $officeDivision->office_division }}</h3>
                                                    </div>
                                                    <div class="relative">
                                                        <button wire:click="toggleEditSettings({{ $officeDivision->id }}, 'office/division')" 
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                            -mr-2 text-xs font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                            focus:outline-none" title="Edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <button wire:click="toggleDeleteSettings({{ $officeDivision->id }}, 'office/division')" 
                                                            class="text-red-600 text-xs hover:text-red-900 dark:text-red-600 
                                                            dark:hover:text-red-900" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                        
                                                <div class="w-full p-4 flex flex-col mb-6 bg-white dark:bg-gray-600">
                                                    <div class="w-full">
                                                        <div class="flex justify-between items-center w-full">
                                                            <h3 class="text-xs font-semibold text-gray-300 dark:text-gray-500">POSITIONS</h3>
                                                            <div class="relative mr-4">
                                                                @if($officeDivision->positions->isNotEmpty())
                                                                    <button wire:click="toggleEditSettings({{ $officeDivision->id }}, 'position')" 
                                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                        -mr-2 text-xs font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                        focus:outline-none" title="Edit Positions">
                                                                        <i class="fas fa-pencil-alt"></i>
                                                                    </button>
                                                                    <button wire:click="toggleDeleteSettings({{ $officeDivision->id }}, 'position')" 
                                                                        class="text-red-600 text-xs hover:text-red-900 dark:text-red-600 
                                                                        dark:hover:text-red-900" title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                @else
                                                                    <button wire:click="toggleAddSettings('position')" 
                                                                        class="text-red-600 text-xs hover:text-red-900 dark:text-red-600 
                                                                        dark:hover:text-red-900" title="Add Position">
                                                                        <i title="Add" class="fas fa-plus text-green-500"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <!-- Positions directly under the Office/Division -->
                                                        @if($officeDivision->positions->isNotEmpty())
                                                            <ul class="ml-4 list-disc">
                                                                @foreach ($officeDivision->positions as $position)
                                                                    <li class="text-sm text-gray-500 dark:text-gray-400">{{ $position->position }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                    <div class="w-full pt-4">
                                                        <h3 class="text-xs font-semibold text-gray-300 dark:text-gray-500">UNITS</h3>
                                                        <!-- Units under the Office/Division -->
                                                        @if($officeDivision->officeDivisionUnits->isNotEmpty())
                                                            <div class="flex overflow-x-auto pb-4">
                                                                @foreach ($officeDivision->officeDivisionUnits as $unit)
                                                                    <!-- Unit Header -->
                                                                    <div class="block px-4 border-l boder-gray-300 dark:border-gray-500">
                                                                        <div class="flex justify-left items-center w-full py-1">
                                                                            <img class="flex dark:hidden" src="/images/unit-dark.png" width="15" alt="">
                                                                            <img class="hidden dark:block" src="/images/unit-light.png" width="15" alt="">
                                                                            <h4 class="ml-2 text-sm text-gray-500 dark:text-gray-300">{{ $unit->unit }}</h4>
                                                                        </div>
                                                    
                                                                        <div class="ml-6 flex justify-between items-center w-full">
                                                                            <h3 class="text-xs font-semibold text-gray-300 dark:text-gray-500">POSITIONS</h3>
                                                                            <div class="relative mr-4 mb-2">
                                                                                <button wire:click="toggleEditSettings({{ $officeDivision->id }}, 'position')" 
                                                                                    class="peer inline-flex items-center justify-center px-4 -m-5 
                                                                                    -mr-2 text-xs font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                                    focus:outline-none" title="Edit Positions">
                                                                                    <i class="fas fa-pencil-alt" style="font-size: 10px"></i>
                                                                                </button>
                                                                                <button wire:click="toggleDeleteSettings({{ $officeDivision->id }}, 'position')" 
                                                                                    class="text-red-600 text-xs hover:text-red-900 dark:text-red-600 
                                                                                    dark:hover:text-red-900" title="Delete">
                                                                                    <i class="fas fa-trash" style="font-size: 10px"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Positions under the Unit -->
                                                                        @if($unit->positions->isNotEmpty())
                                                                            <ul class="ml-12 list-disc">
                                                                                @foreach ($unit->positions as $position)
                                                                                    <li class="text-sm text-gray-500 dark:text-gray-400">{{ $position->position }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                                                                                                
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>                                       

                                            @endforeach
                                        </div>
                                    
                                     </div>
                                    <div x-show="selectedTab === 'sgstep'">
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-full">
                                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl" style="height: 20px">
                                                    <tr class="whitespace-nowrap">
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                            </table>

                                            <div class="p-4">
                                                <div class="grid grid-cols-12 gap-4">

                                                    <!-- SG/STEP -->
                                                    <div class="col-span-full sm:col-span-12  bg-gray-200 dark:bg-gray-700 rounded-lg shadow block">
                                                        <div class="flex flex-col items-center w-full pb-2 my-4">
                                                            <div class="flex justify-left items-center mb-4">
                                                                <h3 class="text-sm font-semibold text-black dark:text-gray-200 uppercase">Salary Grade & Step</h3>
                                                            </div>
                                                            <div class="w-full overflow-hidden border dark:border-gray-700">
                                                                <div class="overflow-x-auto">
                                                                    <table class="min-w-full bg-white dark:bg-gray-500">
                                                                        <thead class="bg-gray-100 dark:bg-slate-800 rounded-xl">
                                                                            <tr class="text-gra7-900 dark:text-gray-100 uppercase leading-normal" style="font-size: 11px">
                                                                                <th class="py-2 px-2 text-left">SG</th>
                                                                                <th class="py-2 px-2 text-left">Step 1</th>
                                                                                <th class="py-2 px-2 text-left">Step 2</th>
                                                                                <th class="py-2 px-2 text-left">Step 3</th>
                                                                                <th class="py-2 px-2 text-left">Step 4</th>
                                                                                <th class="py-2 px-2 text-left">Step 5</th>
                                                                                <th class="py-2 px-2 text-left">Step 6</th>
                                                                                <th class="py-2 px-2 text-left">Step 7</th>
                                                                                <th class="py-2 px-2 text-left">Step 8</th>
                                                                                <th class="py-2 px-2 text-center sticky right-0 z-10 bg-gray-100 dark:bg-slate-800">Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400 dark:bg-gray-700" style="font-size: 11px">
                                                                            @foreach ($salaryGrades as $salaryGrade)
                                                                                <tr class="border-b border-gray-200 hover:bg-gray-100 !hover:text-gray-800">
                                                                                    <td class="py-2 px-2 text-left whitespace-nowrap text-gray-800 dark:text-gray-300">
                                                                                        {{ $salaryGrade->salary_grade }}
                                                                                    </td>
                                                                                    @for ($i = 1; $i <= 8; $i++)
                                                                                        <td class="py-2 px-2 text-left">
                                                                                            {{ number_format($salaryGrade->{"step$i"}, 2) }}
                                                                                        </td>
                                                                                    @endfor
                                                                                    <td class="py-2 px-2 text-center sticky right-0 z-10 bg-white dark:bg-gray-700">
                                                                                        <div class="relative">
                                                                                            <button wire:click="editSG({{ $salaryGrade->id }})" 
                                                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                                                -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                                                focus:outline-none" title="Edit">
                                                                                                <i class="fas fa-pencil-alt"></i>
                                                                                            </button>
                                                                                            <button wire:click="toggleDeleteSG({{ $salaryGrade->id }}, 'salary grade')" 
                                                                                                class=" text-red-600 hover:text-red-900 dark:text-red-600 
                                                                                                dark:hover:text-red-900" title="Delete">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="text-xs flex justify-center items-center mt-4">
                                                                    <button wire:click="openSGModal" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">
                                                                        Add Salary Grade
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Add and Edit Role Modal --}}
    <x-modal id="roleModal" maxWidth="2xl" wire:model="editRole" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addRole ? 'Add' : 'Edit' }} Admin Role
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveRole'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-full sm:col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addRole ? '' : 'disabled' }}>
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="user_role" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Admin Role</label>
                        <select id="userId" wire:model='user_role' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select Role</option>
                            <option value="sa">Super Admin</option>
                            <option value="hr">Human Resource</option>
                            <option value="sv">Supervisor</option>
                            <option value="pa">Payroll</option>
                            @if(!$addRole)
                                <option value="emp">Employee</option>
                            @endif
                        </select>                        
                        @error('user_role') 
                            <span class="text-red-500 text-sm">The account role is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Admin Email</label>
                        <input type="text" id="admin_email" wire:model='admin_email' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('admin_email') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="col-span-full sm:col-span-1">
                        <label for="office_division" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Office/Division</label>
                        <select id="office_division" wire:model='office_division' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option class="text-gray-300" value="{{ $office_division }}">{{ $office_division ? $office_division : 'Select office/division' }}</option>
                            @foreach($officeDivisions as $office)
                                <option value="{{ $office->id }}">{{ $office->office_division }}</option>
                            @endforeach
                        </select>
                        @error('office_division') 
                            <span class="text-red-500 text-sm">Please select office/division!</span> 
                        @enderror
                    </div>

                    @if($addRole)
                        <div class="col-span-full sm:col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Password</label>
                            <input type="password" id="password" wire:model='password' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('password') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="col-span-full sm:col-span-1">
                            <label for="cpassword" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Confirm Password</label>
                            <input type="password" id="cpassword" wire:model='cpassword' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('cpassword') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    @endif

                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveRole" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Add and Edit Office/Division or Position Modal --}}
    <x-modal id="posModal" maxWidth="2xl" wire:model="settings" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold uppercase">
                {{ $add ? 'Add' : 'Edit' }} {{ $data }}
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveSettings'>
                <div class="grid grid-cols-2 gap-4">
                    
                    @if($add)
                        @foreach ($settingsData as $index => $setting)
                            <div class="col-span-2 relative">
                                <label for="settings_data_{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">{{ $data }}</label>
                                <input type="text" id="settings_data_{{ $index }}" wire:model='settingsData.{{ $index }}.value' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                @if (count($settingsData) > 1)
                                    <button type="button" wire:click="removeSetting({{ $index }})" class="absolute right-2 top-8 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                @error('settingsData.' . $index . '.value')
                                    <span class="text-red-500 text-sm">This field is required!</span>
                                @enderror
                            </div>
                            @if($data === "office/division")
                                @foreach ($units as $index => $setting)
                                    <div class="col-span-1 relative">
                                        <label for="unit_{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">Unit</label>
                                        <input type="text" id="unit_{{ $index }}" wire:model='units.{{ $index }}.value' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">

                                        <button type="button" wire:click="removeUnit({{ $index }})" class="absolute right-2 top-8 text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @error('units.' . $index . '.value')
                                            <span class="text-red-500 text-sm">This field is required!</span>
                                        @enderror
                                    </div>
                                @endforeach
                                <div class="col-span-2">
                                    <button type="button" wire:click="addNewUnit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Add Another Unit
                                    </button>
                                </div>
                            @endif
                        @endforeach
                        @if($data === "position")
                            <div class="col-span-2">
                                <button type="button" wire:click="addNewSetting" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Add Another {{ $data }}
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="col-span-2 relative">
                            <label for="settings_data" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">{{ $data }}</label>
                            <input type="text" id="settings_data" wire:model='settings_data' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('settings_data')
                                <span class="text-red-500 text-sm">This field is required!</span>
                            @enderror
                        </div>
                        @if($data === "office/division")
                            @foreach ($units as $index => $setting)
                                <div class="col-span-1 relative">
                                    <label for="unit_{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">Unit</label>
                                    <input type="text" id="unit_{{ $index }}" wire:model='units.{{ $index }}.value' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                     
                                    <button type="button" wire:click="removeUnit({{ $index }})" class="absolute right-2 top-8 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @error('units.' . $index . '.value')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="col-span-2">
                                <button type="button" wire:click="addNewUnit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Add Another Unit
                                </button>
                            </div>
                        @endif
                    @endif

                    <div class="mt-4 flex justify-end col-span-2">
                        <button type="submit" class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveSettings" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <button type="button" @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Delete Modal --}}
    <x-modal id="deleteModal" maxWidth="md" wire:model="deleteId" centered>
        <div class="p-4">
            <div class="mb-4 text-slate-900 dark:text-gray-100 font-bold">
                Confirm Deletion
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                Are you sure you want to delete this {{ $deleteMessage }}?
            </label>
            <form wire:submit.prevent='deleteData'>
                <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                    <button class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <div wire:loading wire:target="deleteData" style="margin-bottom: 5px;">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        Delete
                    </button>
                    <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                        Cancel
                    </p>
                </div>
            </form>

        </div>
    </x-modal>

    {{-- Add Salary Grade Modal --}}
    <x-modal id="addSalaryGradeModal" maxWidth="2xl" wire:model="showSGModal">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold uppercase">
                {{ $isEditing ? 'Edit' : 'Add' }} Salary Grade
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveSalaryGrade'>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label for="salary_grade" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">Salary Grade</label>
                        <input type="number" id="salary_grade" wire:model='salaryGradeData.salary_grade' {{ $isEditing ? 'readonly' : '' }}
                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" required>
                        @error('salaryGradeData.salary_grade') <span class="text-red-500 text-sm">This field is required!</span> @enderror
                    </div>
                    @for ($i = 1; $i <= 8; $i++)
                        <div class="col-span-full sm:col-span-1">
                            <label for="step{{ $i }}" class="block text-sm font-medium text-gray-700 dark:text-slate-400 uppercase">Step {{ $i }}</label>
                            <input type="number" step="0.01" id="step{{ $i }}" wire:model='salaryGradeData.step{{ $i }}' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('salaryGradeData.step'.$i) <span class="text-red-500 text-sm">This field is required!</span> @enderror
                        </div>
                    @endfor
                    <div class="mt-4 flex justify-end col-span-2">
                        <button type="submit" class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveSalaryGrade" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
    
    {{-- Add and Edit Employee Pos Modal --}}
    <x-modal id="empModal" maxWidth="2xl" wire:model="editPosition" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Employee Settings
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='savePosition'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-full">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" style="pointer-events: {{ $addPosition ? 'all' : 'none' }}">
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-full sm:col-span-1">
                        <label for="office_division" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Office/Division</label>
                        <select id="office_division" wire:model='officeDivisionId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option class="text-gray-300" value="{{ $officeDivisionId }}">{{ $office_division ? $office_division : 'Select office/division' }}</option>
                            @foreach($officeDivisions as $office)
                                <option value="{{ $office->id }}">{{ $office->office_division }}</option>
                            @endforeach
                        </select>
                        @error('office_division') 
                            <span class="text-red-500 text-sm">Please select office/division!</span> 
                        @enderror
                    </div>
                    
                    <div class="col-span-full sm:col-span-1">
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                        <select id="position" wire:model='positionId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="{{ $positionId }}">{{ $position ? $position : 'Select position' }}</option>
                            @foreach ($positions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->position }}</option>
                            @endforeach
                        </select>
                        @error('position') 
                            <span class="text-red-500 text-sm">Please select position!</span> 
                        @enderror
                    </div>

                    <div class="text-sm font-semibold text-purple-800 dark:text-gray-100 mb-4">
                        <label for="allColActive" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Select Status: </label>
                        <ul class="flex space-x-4 text-sm mt-2">
                            <li class="flex flex-col sm:flex-row items-center justify-center">
                                <input id="allColActive" type="radio" name="status" value="1" wire:model.live="activeStatus" class="h-4 w-4">
                                <label for="allColActive" class="sm:ml-2 text-gray-900 dark:text-gray-300">Active</label>
                            </li>
                            <li class="flex flex-col sm:flex-row items-center justify-center">
                                <input id="allColInactive" type="radio" name="status" value="0" wire:model.live="activeStatus" class="h-4 w-4">
                                <label for="allColInactive" class="sm:ml-2 text-gray-900 dark:text-gray-300">Inactive</label>
                            </li>
                            <li class="flex flex-col sm:flex-row items-center justify-center">
                                <input id="allColResigned" type="radio" name="status" value="2" wire:model.live="activeStatus" class="h-4 w-4">
                                <label for="allColResigned" class="sm:ml-2 text-gray-900 dark:text-gray-300">Resigned</label>
                            </li>
                            <li class="flex flex-col sm:flex-row items-center justify-center">
                                <input id="allColRetired" type="radio" name="status" value="3" wire:model.live="activeStatus" class="h-4 w-4">
                                <label for="allColRetired" class="sm:ml-2 text-gray-900 dark:text-gray-300">Retired</label>
                            </li>
                        </ul>                    
                    </div>

                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="savePosition" class="spinner-border small text-primary" role="status">
                            </div>
                            Save
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

</div>
