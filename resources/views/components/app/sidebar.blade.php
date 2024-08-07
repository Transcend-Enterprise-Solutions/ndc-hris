<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-white dark:bg-slate-800 p-4 transition-all duration-200 ease-in-out rounded-3-2xl"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-slate-300 hover:text-white" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="flex items-center" href="{{ route('dashboard') }}" style="margin-left: -3px !important;">
                <img class="size-10 mx-auto" src="/images/hris-logo.png" alt="hris logo">
                <span class="text-black dark:text-white font-bold lg:hidden lg:sidebar-expanded:inline"
                    style="margin-left: 10px">
                    NYC - HRIS
                </span>
            </a>
        </div>

        <div class="space-y-8">
            <div>
                <ul class="mt-3">

<!-- Admin Tabs ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    @if(Auth::user()->user_role === 'sa')
                        <!-- Dashboard -->
                        <li class="px-3 py-2  rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['dashboard'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['dashboard']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(Route::is('dashboard')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                href="{{ route('dashboard') }}" wire:navigate>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-speedometer2 text-slate-400 dark:text-slate-300 mr-3"></i>
                                        <span
                                            class="text-sm lg:hidden lg:sidebar-expanded:inline font-medium 
                                            @if(Route::is('dashboard')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            Dashboard
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- Role Management -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['role-management'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['role-management']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(Route::is('role-management')){{ '!text-blue-500' }}@endif"
                                href="{{ route('role-management') }}" wire:navigate>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-person-gear text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Role
                                            Management</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- Employee Management -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['employee-management'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['employee-management']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['employee-management'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-people text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Employee
                                            Mgmt</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                    :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/employee-management/employees')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/employee-management/employees') }}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Employees</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/employee-management/admin-dtr')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/employee-management/admin-dtr')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Daily
                                                Time Record</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/employee-management/admin-doc-request')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/employee-management/admin-doc-request')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Document
                                                Request</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/employee-management/emp-documents')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/employee-management/emp-documents')}}"wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Employee Documents
                                                </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/employee-management/admin-schedule')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/employee-management/admin-schedule')}}"wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Schedule</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Leave Management -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['leave-management'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['leave-management']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['leave-management'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-journal-check text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Leave
                                            Management</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                    :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/leave-management/admin-leave-request')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/leave-management/admin-leave-request') }}"  wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Leave
                                                Request</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/leave-management/admin-leave-records')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/leave-management/admin-leave-records') }}"  wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Leave
                                                Records</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Payroll Management -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['payroll'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['payroll']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['payroll'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-journal-text text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Payroll</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                    :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/payroll/payroll-management')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/payroll/payroll-management')}}"  wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Payroll Management</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/payroll/general-payroll')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/payroll/general-payroll')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                General Payroll
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/payroll/payroll')){{ '!text-blue-500' }}@endif"
                                            href="{{route('/payroll/payroll')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Payroll</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Report Generation -->
                        <li class="px-3 py-2 rounded-lg mb-0 last:mb-0 @if(in_array(Request::segment(1), ['report-generation'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif">
                            <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(Route::is('report-generation')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                href="{{ route('report-generation') }}" wire:navigate>
                                <div class="flex items-center justify-between">
                                    <div class="grow flex items-center">
                                        <i class="bi bi-file-earmark-spreadsheet text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300 
                                            @if(Route::is('report-generation')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Report
                                            Generation</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif

<!-- Employees Tabs ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    
                    @if(Auth::user()->user_role === 'emp')
                        <!-- Home -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['home'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['home']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(Route::is('home')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                href="{{ route('home') }}"  wire:navigate>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-house-fill text-slate-400 dark:text-slate-300 mr-3"></i>
                                        <span
                                            class="text-sm font-medium @if(in_array(Request::segment(1), ['home'])){{ 'text-blue-500 dark:text-blue-500' }}@endif">Home</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- My Records -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['my-records'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['my-records']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['my-records'])){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-journal-check text-slate-400 dark:text-slate-300 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                        :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                        My Records</span>
                                    </div>
                                    <!-- Icon -->
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                    :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('/my-records/personal-data-sheet')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            href="{{ route('/my-records/personal-data-sheet') }}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            Personal Data Sheet</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('/my-records/my-documents')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            href="{{route('/my-records/my-documents')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            My Documents</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('/my-records/doc-request')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            href="{{route('/my-records/doc-request')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            Document Request</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- DTR -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['daily-time-record'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['daily-time-record']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['daily-time-record'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-clock text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                        :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            Daily Time Record</span>
                                    </div>
                                    <!-- Icon -->
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('/daily-time-record/wfh-attendance')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                            href="{{route('/daily-time-record/wfh-attendance')}}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                WFH Attendance
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/daily-time-record/dtr')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/daily-time-record/dtr') }}"wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                DTR
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/daily-time-record/my-schedule')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/daily-time-record/my-schedule') }}"wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                My Schedule
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Filing and Approval -->
                        <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['filing-and-approval'])){{ 'bg-gray-200 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['filing-and-approval']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['filing-and-approval'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-card-checklist text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                        :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                        Filing and Approval</span>
                                    </div>
                                    <!-- Icon -->
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="lg:hidden lg:sidebar-expanded:inline w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-9 mt-1 transition-all duration-300 overflow-hidden"
                                :class="{'max-h-0': !open, 'max-h-screen': open}">
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/filing-and-approval')){{ '!text-blue-500' }}@endif"
                                            href="#0">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                            Overtime
                                        </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/filing-and-approval/leave-application')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/filing-and-approval/leave-application') }}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                Leave
                                            </span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('/filing-and-approval/leave-monetization')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('/filing-and-approval/leave-monetization') }}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">
                                                Leave Monetization
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                        <path class="text-slate-400"
                            d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
                        <path class="text-slate-600" d="M3 23H1V1h2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
