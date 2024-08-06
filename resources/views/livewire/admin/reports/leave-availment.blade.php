<div
    class="flex flex-col col-span-full sm:col-span-6 bg-white dark:bg-slate-800 shadow-lg rounded-lg 
    border border-slate-200 dark:border-slate-700">
    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <div class="relative inline-flex" x-data="{ open: false }">
                <button class="rounded-full"
                    :class="open ? 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400': 
                    'text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400'"
                    aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
                    <span class="sr-only">Menu</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                        <circle cx="16" cy="16" r="2" />
                        <circle cx="10" cy="16" r="2" />
                        <circle cx="22" cy="16" r="2" />
                    </svg>
                </button>
            </div>
        </header>
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-2">Leave Availment Report</h2>
  
    </div>

    <div class="grow max-sm:max-h-[128px] xl:max-h-[128px]">
        <canvas id="dashboard-card-03" width="389" height="128"></canvas>
    </div>
</div>
