<div class="w-full">

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Role Management</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Role Search Input --}}
                <div class="w-full sm:w-1/3 sm:mr-4">
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
                <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">

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
                
            </div>

            <!-- Table -->
            <div class="w-full">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto">
                        <div class="inline-block w-full py-2 align-middle">
                            <div>
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">
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
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
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
