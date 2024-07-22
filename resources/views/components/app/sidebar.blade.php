<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div
        class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        aria-hidden="true"
        x-cloak
    ></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-white dark:bg-slate-800 p-4 transition-all duration-200 ease-in-out rounded-3-2xl"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

        <!-- Sidebar header -->
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

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-slate-400 dark:text-slate-200 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
                </h3>

                <ul class="mt-3">
                    <!-- Dashboard -->
                    @if(Auth::user()->user_role === 'sa')
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['dashboard'])){{ 'bg-slate-700 dark:bg-slate-900' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['dashboard']) ? 1 : 0 }} }">
                        <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(Route::is('dashboard')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                            href="{{ route('dashboard') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="bi bi-speedometer2 text-slate-400 dark:text-slate-300 mr-3"></i>
                                    <span
                                        class="text-sm lg:hidden lg:sidebar-expanded:inline font-medium @if(Route::is('dashboard')){{ 'text-blue-500 dark:text-blue-500' }}@endif">Dashboard</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    @endif

                    <!-- Employees Tabs -->
                    @if(Auth::user()->user_role === 'emp')
                    <!-- Home -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['home'])){{ 'bg-slate-700 dark:bg-slate-900' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['home']) ? 1 : 0 }} }">
                        <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['home'])){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                            href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
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
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['ecommerce'])){{ 'bg-slate-700 dark:bg-slate-900' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['ecommerce']) ? 1 : 0 }} }">
                        <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['ecommerce'])){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                            href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="bi bi-journal-check text-slate-400 dark:text-slate-300 mr-3"></i>
                                    <span
                                        class="text-sm font-medium @if(in_array(Request::segment(1), ['ecommerce'])){{ 'text-blue-500 dark:text-blue-500' }}@endif">My
                                        Records</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 dark:text-slate-300 @if(in_array(Request::segment(1), ['ecommerce'])){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['ecommerce'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('personal-data-sheet')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                        href="{{ route('personal-data-sheet') }}" wire:navigate>
                                        <span
                                            class="text-sm font-medium @if(Route::is('personal-data-sheet')){{ 'text-blue-500 dark:text-blue-500' }}@endif">Personal
                                            Data Sheet</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('my-documents')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                        href="{{route('my-documents')}}">
                                        <span
                                            class="text-sm font-medium @if(Route::is('my-documents')){{ 'text-blue-500 dark:text-blue-500' }}@endif">My
                                            Documents</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 truncate @if(Route::is('doc-request')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                        href="{{route('doc-request')}}">
                                        <span
                                            class="text-sm font-medium @if(Route::is('doc-request')){{ 'text-blue-500 dark:text-blue-500' }}@endif">Document
                                            Request</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- DTR -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['community'])){{ 'bg-slate-700' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['community']) ? 1 : 0 }} }">
                        <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['community'])){{ 'text-blue-500' }}@endif"
                            href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="bi bi-clock text-slate-400 mr-3"></i>
                                    <span
                                        class="text-sm font-medium @if(in_array(Request::segment(1), ['community'])){{ 'text-blue-500' }}@endif">Daily
                                        Time Record</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['community'])){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['community'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('dtr')){{ '!text-blue-500' }}@endif"
                                        href="{{ route('dtr') }}">
                                        <span
                                            class="text-sm font-medium @if(Route::is('users-tabs')){{ 'text-blue-500' }}@endif">DTR</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('users-tiles')){{ '!text-blue-500' }}@endif"
                                        href="#0">
                                        <span
                                            class="text-sm font-medium @if(Route::is('users-tiles')){{ 'text-blue-500' }}@endif">Payslip</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Filing and Approval -->
                    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['finance'])){{ 'bg-slate-700' }}@endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['finance']) ? 1 : 0 }} }">
                        <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['finance'])){{ 'text-blue-500' }}@endif"
                            href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="bi bi-card-checklist text-slate-400 mr-3"></i>
                                    <span
                                        class="text-sm font-medium @if(in_array(Request::segment(1), ['finance'])){{ 'text-blue-500' }}@endif">Filing
                                        and Approval</span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 @if(in_array(Request::segment(1), ['finance'])){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-9 mt-1 @if(!in_array(Request::segment(1), ['finance'])){{ 'hidden' }}@endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('credit-cards')){{ '!text-blue-500' }}@endif"
                                        href="#0">
                                        <span
                                            class="text-sm font-medium @if(Route::is('credit-cards')){{ 'text-blue-500' }}@endif">Overtime</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('leave-application')){{ '!text-blue-500' }}@endif"
                                        href="{{ route('leave-application') }}">
                                        <span
                                            class="text-sm font-medium @if(Route::is('leave-application')){{ 'text-blue-500' }}@endif">Leave</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-black dark:text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('transaction-details')){{ '!text-blue-500' }}@endif"
                                        href="#0">
                                        <span
                                            class="text-sm font-medium @if(Route::is('transaction-details')){{ 'text-blue-500' }}@endif">Leave
                                            Monetization</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    <!-- Admin Tabs -->
                    @if(Auth::user()->user_role === 'sa')
                        <!-- Role Management -->
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['tasks'])){{ 'bg-slate-700' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['tasks']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['tasks'])){{ 'text-blue-500' }}@endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
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
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['job'])){{ 'bg-slate-700' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['job']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['job'])){{ 'text-blue-500' }}@endif"
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
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('employees')){{ '!text-blue-500' }}@endif"
                                            href="{{ route('employees') }}" wire:navigate>
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Employees</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('admin-dtr')){{ '!text-blue-500' }}@endif"
                                            href="{{route('admin-dtr')}}">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Daily
                                                Time Record</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('admin-doc-request')){{ '!text-blue-500' }}@endif"
                                            href="{{route('admin-doc-request')}}">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Document
                                                Request</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('admin-schedule')){{ '!text-blue-500' }}@endif"
                                            href="{{route('admin-schedule')}}">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Schedule</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Leave Management -->
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['leave'])){{ 'bg-slate-700' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['leave']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['leave'])){{ 'text-blue-500' }}@endif"
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
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('leave-request')){{ '!text-blue-500' }}@endif"
                                            href="#0">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Leave
                                                Request</span>
                                        </a>
                                    </li>
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-blue-500 transition duration-150 truncate @if(Route::is('leave-records')){{ '!text-blue-500' }}@endif"
                                            href="#0">
                                            <span class="text-sm font-medium transition-opacity duration-300"
                                                :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Leave
                                                Records</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Payroll Management -->
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if(in_array(Request::segment(1), ['payroll-management'])){{ 'bg-slate-700 dark:bg-slate-900' }}@endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['payroll-management']) ? 1 : 0 }} }">
                            <a class="block text-black dark:text-white hover:text-blue-500 dark:hover:text-blue-500 transition duration-150 @if(Route::is('payroll-management')){{ 'text-blue-500 dark:text-blue-500' }}@endif"
                                href="{{ route('payroll-management') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="bi bi-journal-text text-slate-400 mr-3"></i>
                                        <span
                                            class="text-sm lg:hidden lg:sidebar-expanded:inline font-medium @if(Route::is('payroll-management')){{ 'text-blue-500 dark:text-blue-500' }}@endif">Payroll Management</span>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <!-- Report Generation -->
                        <li
                            class="px-3 py-2 rounded-sm mb-0 last:mb-0 @if(in_array(Request::segment(1), ['report'])){{ 'bg-slate-700' }}@endif">
                            <a class="block text-black dark:text-white hover:text-blue-500 transition duration-150 @if(in_array(Request::segment(1), ['report'])){{ 'text-blue-500' }}@endif"
                                href="#0">
                                <div class="flex items-center justify-between">
                                    <div class="grow flex items-center">
                                        <i class="bi bi-file-earmark-spreadsheet text-slate-400 mr-3"></i>
                                        <span class="text-sm font-medium transition-opacity duration-300"
                                            :class="sidebarExpanded ? 'opacity-100 lg:inline' : 'opacity-0 lg:hidden'">Report
                                            Generation</span>
                                    </div>
                                </div>
                            </a>
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
