<div class="w-full">
    <div class="w-full flex flex-col justify-center items-center">
        <div id="clock" class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
            <!-- Time will be displayed here -->
        </div>

        <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">NYC WFH ATTENDANCE</h5>
            <div class="grid grid-cols-1 gap-4 p-4">
                <button wire:click="morningIn" class="inline-block px-3 py-1 text-sm font-semibold text-green-200 bg-green-700 rounded">Morning In</button>
                <button wire:click="morningOut" class="inline-block px-3 py-1 text-sm font-semibold text-green-200 bg-green-700 rounded">Morning Out</button>
                <button wire:click="afternoonIn" class="inline-block px-3 py-1 text-sm font-semibold text-blue-200 bg-blue-700 rounded">Afternoon In</button>
                <button wire:click="afternoonOut" class="inline-block px-3 py-1 text-sm font-semibold text-blue-200 bg-blue-700 rounded">Afternoon Out</button>
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
    
    // Update the clock immediately and then every second
    updateClock();
    setInterval(updateClock, 1000);
</script>
