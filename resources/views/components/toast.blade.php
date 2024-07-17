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
                console.log('Message:', this.message);
                console.log('Type:', this.type);
                setTimeout(() => this.show = false, 3000);
            }
        }
    }"
    x-on:notify.window="showToast($event.detail)"
    x-show="show"
    x-transition
    x-cloak
    class="fixed top-4 right-10 p-4 rounded shadow-lg z-50"
    :class="{
        'bg-green-500 text-white': type === 'success',
        'bg-red-500 text-white': type === 'error',
        'bg-blue-500 text-white': type === 'info'
    }"
>
    <p x-text="message"></p>
</div>