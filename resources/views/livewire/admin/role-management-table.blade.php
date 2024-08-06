<div class="w-full" x-data="{ selectedTab: 'role' }" x-cloak>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Role Management</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Role Search Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'role'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Payroll Search Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'payroll'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search2"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Payslip Search Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4" x-show="selectedTab === 'payslip'">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search3"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

                {{-- Role Add & Export --}}
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

                {{-- Payroll Add & Export --}}
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'payroll'">

                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddSignatory" 
                            class="mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add Payroll Signatory
                        </button>
                    </div>

                    <!-- Export to Excel -->
                    {{-- <div class="relative inline-block text-left">
                        <button wire:click="exportPayrollSignatory"
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

                {{-- Payslip Add & Export --}}
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4" x-show="selectedTab === 'payslip'">

                    <div class="w-full sm:w-auto">
                        <button wire:click="toggleAddPayslipSignatory" 
                            class="mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md 
                            hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full
                            dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Add Payslip Signatory
                        </button>
                    </div>

                    <!-- Export to Excel -->
                    {{-- <div class="relative inline-block text-left">
                        <button wire:click="exportPayrollSignatory"
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
                <div class="flex gap-2 overflow-x-auto -mb-2">
                    <button @click="selectedTab = 'role'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'role', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'role' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Account Role
                    </button>
                    <button @click="selectedTab = 'payroll'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'payroll', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'payroll' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Payroll Signatory
                    </button>
                    <button @click="selectedTab = 'payslip'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'payslip', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'payslip' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm">
                        Payslip Signatory
                    </button>
                </div>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto">
                        <div class="inline-block w-full py-2 align-middle">
                            <div x-show="selectedTab === 'role'">
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col" class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                        Account Role
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
                                                            {{ $admin->employee_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $admin->office_division }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $admin->position }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <div class="relative">
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
                            </div>
                            <div x-show="selectedTab === 'payroll'">
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                        Payroll Signatory
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
                                                @foreach ($payrollSignatories as $sign)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->signatory }}
                                                        </td>
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->name }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->employee_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->office_division }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->position }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <div class="relative">
                                                                <button wire:click="toggleEditSignatory({{ $sign->user_id }})" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none" title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                                <button wire:click="toggleDelete({{ $sign->user_id }}, 'payroll signatory')" 
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
                                        @if ($payrollSignatories->isEmpty())
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                No records!
                                            </div> 
                                        @endif
                                    </div>
                                    <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                        {{ $payrollSignatories->links() }}
                                    </div>
                                </div>
                            </div>
                            {{-- Payroll View --}}
                            <div x-show="selectedTab === 'payroll'">
                                <div class="overflow-hidden mt-10">
                                    <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                        <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Payroll Footer View</h1>
                                    </div>
                                    <div class="overflow-x-auto bg-white text-xs text-black border border-black">
                                        <div class="block sm:flex">
                                            <div class="col-span-1 block border-r border-black">
                                                <div class="h-32 w-full block border-b border-black relative">
                                                    <div class="flex">
                                                        <p class="px-2 border-r border-b border-black">A.</p>
                                                        <p class="pl-2">CERTIFIED: Services duly rendered as stated.</p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            <p class="text-center font-bold text-sm">{{ $signs['a'] ? $signs['a']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $signs['a'] ? $signs['a']->position : 'Position' }}</p>
                                                        </div>
                                                        <div class="flex flex flex-col items-center w-1/5">
                                                            <p class="text-center underline">01/01/2024</p>
                                                            <p class="text-center">Date</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="h-32 w-full relative">
                                                    <div class="flex">
                                                        <p class="px-2 border-r border-b border-black">B.</p>
                                                        <p class="pl-2">CERTIFIED: Supporting documents complete and proper; and cash available in the amount of</p>
                                                    </div>
                                                    <div class="flex">
                                                        <p class="px-2"></p>
                                                        <p class="pl-6 font-bold">₱ 4,884.16</p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            <p class="text-center font-bold text-sm">{{ $signs['b'] ? $signs['b']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $signs['b'] ? $signs['b']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-1 block">
                                                <div class="h-32 w-full border-b border-black relative">
                                                    <div class="flex">
                                                        <p class="px-2 border-r border-b border-black">C.</p>
                                                        <p class="pl-2 font-bold">APPROVED FOR PAYMENT: FOUR THOUSAND, EIGHT HUNDRED AND EIGHTY-FOUR AND 16/100 PESOS ONLY</p>
                                                    </div>
                                                    <div class="flex">
                                                        <p class="px-2"></p>
                                                        <p class="pl-6 font-bold">₱ 4,884.16</p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            <p class="text-center font-bold text-sm">{{ $signs['c'] ? $signs['c']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $signs['c'] ? $signs['c']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="h-32 w-full relative">
                                                    <div class="flex">
                                                        <p class="px-2 border-r border-b border-black">D.</p>
                                                        <p class="pl-2">CERTIFIED: Each employee whose name <br>
                                                            appears above has been paid the amount indicated <br>
                                                            opposite on his/her name.
                                                        </p>
                                                    </div>
                                                    <div class="absolute bottom-1 w-full flex">
                                                        <div class="flex flex flex-col items-center w-4/5">
                                                            <p class="text-center font-bold text-sm">{{ $signs['d'] ? $signs['d']->name : 'XXXXXXXXXX' }}</p>
                                                            <p class="text-center">{{ $signs['d'] ? $signs['d']->position : 'Position' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="selectedTab === 'payslip'">
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                        Payslip Signatory
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
                                                @foreach ($payslipSignatories as $sign)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->signatory }}
                                                        </td>
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->name }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->employee_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->office_division }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $sign->position }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <div class="relative">
                                                                <button wire:click="toggleEditPayslipSignatory({{ $sign->user_id }})" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                    focus:outline-none"  title="Edit">
                                                                    <i class="fas fa-pencil-alt ml-3"></i>
                                                                </button>
                                                                <button wire:click="toggleDelete({{ $sign->user_id }}, 'payslip signatory')" 
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
                                        @if ($payslipSignatories->isEmpty())
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                No records!
                                            </div> 
                                        @endif
                                    </div>
                                    <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                        {{ $payslipSignatories->links() }}
                                    </div>
                                </div>
                            </div>
                            {{-- Payslip View --}}
                            <div x-show="selectedTab === 'payslip'">
                                <div class="overflow-hidden mt-10">
                                    <div class="pb-4 mb-3 pt-4 sm:pt-0">
                                        <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Payslip Footer View</h1>
                                    </div>
                                    <div class="overflow-x-auto bg-white text-xs text-black border border-black px-2 py-6">
                                        <div class="block">
                                            <div class="flex border-b border-dashed border-black">
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 font-bold">NET PAY</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 border-b font-bold border-solid border-black text-right">₱ 0,000.00</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4">Amount Due &nbsp&nbsp&nbsp&nbsp January 01-15 2024</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 border-b border-solid border-black text-right">₱ 0,000.00</div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4">Amount Due &nbsp&nbsp&nbsp&nbsp January 16-31 2024</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 border-b border-solid border-black text-right">₱ 0,000.00</div>
                                            </div>
                                            <div class="flex mt-6">
                                                <div class="w-1/4">Prepared By:</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4">Noted By:</div>
                                                <div class="w-1/4"></div>
                                            </div>
                                            <div class="flex mt-6">
                                                <div class="w-1/4 font-bold text-sm">Logged-in Admin</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4 font-bold text-sm">{{ $payslipSigns['notedBy'] ? $payslipSigns['notedBy']->name : 'XXXXXXXXXX' }}</div>
                                                <div class="w-1/4"></div>
                                            </div>
                                            <div class="flex">
                                                <div class="w-1/4">Position</div>
                                                <div class="w-1/4"></div>
                                                <div class="w-1/4">{{ $payslipSigns['notedBy'] ? $payslipSigns['notedBy']->position : 'Position' }}</div>
                                                <div class="w-1/4"></div>
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
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editRole" centered>
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
                    
                    <div class="col-span-1">
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

                    <div class="col-span-1">
                        <label for="user_role" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Account Role</label>
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

                    <div class="col-span-1">
                        <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Admin Email</label>
                        <input type="text" id="admin_email" wire:model='admin_email' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('admin_email') 
                            <span class="text-red-500 text-sm">The admin email is required!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Department</label>
                        <input type="text" id="department" wire:model='department' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('department') 
                            <span class="text-red-500 text-sm">The department is required!</span> 
                        @enderror
                    </div>
                    @if($addRole)
                        <div class="col-span-1">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Password</label>
                            <input type="password" id="password" wire:model='password' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('password') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="col-span-1">
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

    {{-- Add and Edit Payroll Signatory Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editSignatory" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addSignatory ? 'Add' : 'Edit' }} Payroll Signatory
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='saveSignatory'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addSignatory ? '' : 'disabled' }}>
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="signatory" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Payroll Signatory</label>
                        <select id="userId" wire:model='signatory' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select payroll section</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                            @if(!$addSignatory)
                                <option value="X">Remove Signatory</option>
                            @endif
                        </select>                        
                        @error('signatory') 
                            <span class="text-red-500 text-sm">The signatory is required!</span> 
                        @enderror
                    </div>


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

    {{-- Add and Edit Payslip Signatory Modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="editPayslipSignatory" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addPayslipSignatory ? 'Add' : 'Edit' }} Payslip Signatory
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='savePayslipSignatory'>
                <div class="grid grid-cols-2 gap-4">
                    
                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <select id="userId" wire:model='userId' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700"
                            {{ $addPayslipSignatory ? '' : 'disabled' }}>
                            <option value="{{ $userId }}">{{ $name ? $name : 'Select an employee' }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') 
                            <span class="text-red-500 text-sm">Please select an employee!</span> 
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="signatory" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Payslip Signatory</label>
                        <select id="userId" wire:model='signatory' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            <option value="">Select payslip signatory for</option>
                            <option value="Noted By">Noted By</option>
                            @if(!$addPayslipSignatory)
                                <option value="X">Remove Signatory</option>
                            @endif
                        </select>                        
                        @error('signatory') 
                            <span class="text-red-500 text-sm">The signatory is required!</span> 
                        @enderror
                    </div>


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

</div>
