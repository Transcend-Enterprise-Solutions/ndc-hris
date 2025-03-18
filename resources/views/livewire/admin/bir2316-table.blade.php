<div class="w-full flex flex-col justify-center"
x-data="{ 
    selectedTab: 'bir'
}" 
x-cloak>

    <style>
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
            color: rgb(255, 255, 255);
        }

        .spinner-border-2 {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(0, 0, 0);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            @if($pdfContent)

                <div class="mb-8 flex flex-col sm:flex-row items-center justify-between relative">
                    <p class="text-md">Employee: <span class="text-gray-800 dark:text-gray-50">{{ $employeeName }}</span></p>
                    <button wire:click="closeBIR2316"
                        class="text-black dark:text-white whitespace-nowrap mx-2">
                        <i class="bi bi-x-circle" title="Close"></i>
                    </button>
                </div>

                <div class="mt-2" style="overflow: hidden;">
                    <div class="flex gap-2 overflow-x-auto -mb-2">
                        <button @click="selectedTab = 'bir'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'bir', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'bir' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm no-wrap">
                            BIR 2316
                        </button>
                        <button @click="selectedTab = 'summary'" 
                                :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'summary', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'summary' }" 
                                class="h-min px-4 pt-2 pb-4 text-sm">
                            Tax Summary
                        </button>
                    </div>
                    <div x-show="selectedTab === 'bir'" class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <iframe id="pdfIframe" src="data:application/pdf;base64,{{ $pdfContent }}"
                            style="width: 100%; max-height: 80vh; min-height: 500px;" frameborder="0"></iframe>
                    </div>
                </div>
            
            @else

                <div class="pb-4 mb-3 pt-4 sm:pt-0">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">BIR 2316</h1>
                </div>

                <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                        {{-- Search COS Input --}}
                        <div class="w-full sm:w-1/3 sm:mr-4">
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                            <input type="text" id="search" wire:model.live="search"
                                class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                                    dark:hover:bg-slate-600 dark:border-slate-600
                                    dark:text-gray-300 dark:bg-gray-800"
                                placeholder="Enter employee name or ID">
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row items-end justify-between">
                        <!-- Start Date -->
                        <div class="col-span-1">
                            <label for="startDate" class="text-sm font-medium text-gray-700 dark:text-slate-400">From</label>
                            <input type="month" id="startDate" wire:model.live='startDate' value="{{ $startMonth }}"
                                class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                                dark:hover:bg-slate-600 dark:border-slate-600
                                dark:text-gray-300 dark:bg-gray-800">
                        </div>

                            <!-- End Date -->
                        <div class="col-span-1">
                            <label for="endDate" class="text-sm font-medium text-gray-700 dark:text-slate-400">To</label>
                            <input type="month" id="endDate" wire:model.live='endDate' value="{{ $endMonth }}"                            
                                class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                                dark:hover:bg-slate-600 dark:border-slate-600
                                dark:text-gray-300 dark:bg-gray-800">
                        </div>
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
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                        Employee No.
                                                    </th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                        Date Employed
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 text-gray-100 text-sm font-medium text-center sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                @foreach ($employees as $user)
                                                    <tr class="text-sm whitespace-nowrap">
                                                        <td class="px-4 py-2 text-left"> 
                                                            {{ $user->surname }}, {{ $user->first_name }}{{ $user->middle_name ? ' ' . $user->middle_name : ' ' }}{{ $user->name_extention ? ' ' . $user->name_extention : ' ' }}
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->emp_code }}
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $user->date_hired ? \Carbon\Carbon::parse($user->date_hired)->format('F d, Y') : '' }}
                                                        </td>
                
                                                        <td
                                                            class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <button wire:click="showPDF({{ $user->user_id }})"
                                                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                                <i class="fas fa-eye" title="Show Details"></i>
                                                            </button>

                                                            <div class="relative mt-2" style="margin-right: -2px;">
                                                                <button
                                                                    wire:click="toggleExportOption({{ $user->user_id }})"
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2
                                                                    text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                                    title="Export Service Record" wire:target="toggleExportOption({{ $user->user_id }})">
                                                                    <img class="flex dark:hidden ml-3"
                                                                        src="/images/icons8-xls-export-dark.png"
                                                                        width="18" height="18" alt="">
                                                                    <img class="hidden dark:block ml-3"
                                                                        src="/images/icons8-xls-export-light.png"
                                                                        width="18" height="18" alt="">
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($employees->isEmpty())
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                No records!
                                            </div> 
                                        @endif
                                    </div>
                                    <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                        {{ $employees->links() }}
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</div>

<script>
    function resizeIframe() {
        const iframe = document.getElementById('pdfIframe');
        const pdfDocument = iframe.contentDocument || iframe.contentWindow.document;

        if (pdfDocument) {
            // Set the iframe height based on the content
            iframe.style.height = pdfDocument.body.scrollHeight + 'px';
        }
    }

    // Adjust iframe size when the PDF is loaded
    document.getElementById('pdfIframe').onload = resizeIframe;

    // Optional: Adjust iframe size when the window is resized
    window.onresize = resizeIframe;
</script>
