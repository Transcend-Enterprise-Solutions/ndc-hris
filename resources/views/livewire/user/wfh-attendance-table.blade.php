<div x-data="{ open: false }" class="w-full">
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="w-full flex flex-col justify-center items-center">
                    <div id="clock" class="text-lg font-semibold mb-4 text-gray-900 dark:text-white h-10 text-center">
                        <!-- Time will be displayed here -->
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/nyclogo.png" alt="Left Image" class="hidden lg:block w-64 h-auto mx-16">
                        
                        <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700 relative">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">NYC WFH ATTENDANCE</h5>
                            <div class="grid grid-cols-1 gap-4 p-4">
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('morningIn')" class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64"> 
                                        <span class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64">
                                            Morning In
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('morningOut')" class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64"> 
                                        <span class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64">
                                            Morning Out
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('afternoonIn')" class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64"> 
                                        <span class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64">
                                            Afternoon In
                                        </span>
                                    </button>
                                </div>
                                <div class="flex justify-center">
                                    <button wire:click="confirmPunch('afternoonOut')" class="relative inline-flex items-center justify-center p-0.5 mb-2 mx-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 w-48 lg:w-64"> 
                                        <span class="relative px-10 py-2.5 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 w-48 lg:w-64">
                                            Afternoon Out
                                        </span>
                                    </button>
                                </div>
                            </div>
                            @if (!$isWFHDay)
                                <div class="absolute inset-0 flex justify-center items-center bg-gray-700 bg-opacity-75">
                                    <div class="text-center">
                                        <i class="bi bi-person-lock text-white" style="font-size: 5rem;"></i>
                                        <p class="mt-2 text-white font-bold">WFH is not available today</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <img src="images/hris-logo.png" alt="Right Image" class="hidden lg:block w-64 h-auto mx-16">
                    </div>
                </div>

                <x-modal id="passwordConfirmation" maxWidth="md" centered wire:model="inputPassword">
                    <div class="p-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-100">Verify Password</label>
                        <input type="password" wire:model="password" class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" placeholder="Enter your password">
                        
                        @if ($errorMessage)
                            <div class="mt-2 text-red-600 text-sm font-medium">{{ $errorMessage }}</div>
                        @endif
                
                        <button wire:click="verifyPassword" class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mt-4">Submit</button>
                        <button wire:click="closeVerification" class="btn bg-gray-200 dark:bg-gray-600 hover:bg-gray-700 text-gray-800 dark:text-white whitespace-nowrap mt-4">Cancel</button>
                    </div>
                </x-modal>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true 
        };
        const timeString = now.toLocaleString('en-US', options);
        document.getElementById('clock').textContent = timeString;
    }
    
    updateClock();
    setInterval(updateClock, 1000);
</script>
