<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'info',
        showToast(detail) {
            if (detail && detail.length > 0) {
                this.show = true;
                this.message = detail[0].message;
                this.type = detail[0].type;
                setTimeout(() => this.show = false, 3000);
            }
        }
    }"
    x-on:notify.window="showToast($event.detail)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    x-cloak
    class="fixed top-4 right-10 p-3 rounded-lg shadow-lg z-50 bg-white flex items-center"
>
    <div class="flex-shrink-0 mr-3">
        <div class="w-8 h-8 rounded-full flex items-center justify-center"
             :class="{
                 'bg-green-500': type === 'success',
                 'bg-red-500': type === 'error',
                 'bg-blue-500': type === 'info'
             }">
            <svg x-show="type === 'success'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <svg x-show="type === 'info'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <p x-text="message" class="text-gray-800 text-sm"></p>
</div>