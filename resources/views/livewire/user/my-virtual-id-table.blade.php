<div
    class="flex justify-center items-center min-h-screen bg-gray-200 flex flex-col w-full bg-white rounded-xl p-6 shadow-lg dark:bg-gray-800">

    <!-- Header with Dropdown -->
    <div class="w-full mb-10 text-center relative">
        <h1 class="text-lg font-bold text-slate-800 dark:text-white">Virtual ID</h1>

        <!-- Dropdown Toggle Button -->
        <div class="absolute top-0 right-0">
            <div class="relative">
                <button wire:click="toggleDropdown"
                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 focus:outline-none">
                    <i class="bi bi-three-dots-vertical text-slate-800 dark:text-white"></i>
                </button>

                <!-- Dropdown Menu -->
                <div wire:click.away="closeDropdown"
                    class="absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-slate-700 ring-1 ring-black ring-opacity-5 z-50 {{ $showDropdown ? 'block' : 'hidden' }}">
                    <div class="p-2">
                        <button onclick="exportFront()"
                            class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                            Export Front ID
                        </button>
                        <button onclick="exportBack()"
                            class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                            Export Back ID
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Front Side -->
        <div class="w-[550px] h-[340px] bg-white p-4 shadow-lg border rounded-lg relative"
            style="background-image: url('/images/id-bg.png');">
            <h2 class="text-2xl font-bold text-black text-left ml-8 tracking-normal">{{ $name }}</h2>
            <p class="text-sm text-left ml-8 text-black tracking-tighter font-bold">{{ $office_or_department }}</p>

            <!-- Flex Container: Ensures Vertical Centering -->
            <div class="flex items-center h-[250px] ml-4">
                <div class="flex flex-col items-center space-y-1">
                    <!-- Picture Box -->
                    <div class="w-40 h-40 border border-gray-400 flex items-center justify-center bg-white mt-2">
                        @if ($profilePhotoPath)
                            <img src="{{ $profilePhotoPath ? asset('storage/' . $profilePhotoPath) : asset('default-avatar.png') }}"
                                alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                            <span class="text-green-500">Picture</span>
                        @endif
                    </div>

                    <!-- SIGN HERE -->
                    @if ($eSignaturePath)
                        <div class="flex items-center justify-center" style="height: 48px;">
                            <!-- Fixed height container -->
                            <img src="{{ asset($eSignaturePath) }}" alt="E-Signature"
                                class="max-w-full max-h-full object-contain">
                        </div>
                    @else
                        <div class="flex items-center justify-center" style="height: 48px;">
                            <p class="text-red-500 text-sm">SIGN HERE</p>
                        </div>
                    @endif

                    <!-- ID Number -->
                    <p class="text-sm text-black">ID NO. <span class="underline">{{ $emp_code }}</span></p>
                </div>
            </div>

            <div class="absolute top-[230px] right-7 transform -translate-y-1/2 flex flex-col items-center text-center">
                <img src="/images/ndc-logo-transparent.png" class="h-[120px]" alt="">
                <p class="text-xs text-black -mt-2">
                    NDC Building, 116 Tordesillas St.,<br> Salcedo Village, Makati City, Philippines 1227
                </p>
                <p class="text-xs text-black">T (632) 8840-4838 to 62</p>
            </div>
        </div>

        <!-- Back Side -->
        <div class="w-[550px] h-[340px] bg-white p-4 shadow-lg border rounded-lg relative"
            style="background-image: url('/images/id-bg.png');">
            <!-- Flex Container for Left and Right Alignment -->
            <div class="flex justify-between items-center m-4">
                <!-- Left Side: Emergency Info (70%) -->
                <div class="w-[70%]">
                    <h2 class="text-sm font-bold text-black">IN CASE OF EMERGENCY, PLEASE NOTIFY:</h2>
                    <p class="text-sm font-bold text-black">NAME: <span class="font-normal">EMPLOYEE RELATIVE</span></p>
                    <p class="text-sm font-bold text-black">TEL. NO.: <span class="font-normal">09123456789</span></p>
                </div>

                <!-- Right Side: QR Code (30%) -->
                <div class="w-[30%] flex items-center justify-center">
                    <div class="w-24 h-24 flex items-center justify-center">
                        {!! $qrCode !!}
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center m-4">
                <div class="w-[65%]">
                    <p class="text-sm text-black text-justify tracking-tight leading-none">
                        This certifies that the person whose name, picture, and signature appear on this card is an
                        employee of the <span class="font-bold">National Development Company.</span>
                    </p>
                </div>

                <div class="w-[30%] flex items-center justify-center space-x-2">
                    <div class="w-[60px] h-[60px] flex items-center justify-center">
                        <img src="/images/dti-logo.png" alt="dti-logo">
                    </div>

                    <div class="w-[55px] h-[55px] flex items-center justify-center">
                        <img src="/images/tuv.png" alt="tuv-logo">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center m-4">
                <div class="w-[65%]">
                    <p class="text-sm text-black text-justify tracking-tight leading-none">
                        Report loss of card to the Human Resources Unit for immediate replacement. Finder of this is
                        requested to return it to the National Development Company or call (02) 8840-4838.
                    </p>
                </div>

                <div class="w-[35%] flex flex-col items-center text-center justify-center space-x-2">
                    <p class="text-red-500 font-bold">SIGN</p>
                    <p class="text-[11px] text-black font-bold">Atty. RHOEL Z. MABAZZA</p>
                    <p class="text-[10px] text-black font-bold">Assistant General Manager</p>
                    <p class="text-[10px] text-black font-bold">Corporate Support Group</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportFront() {
        // Capture the front side of the ID
        html2canvas(document.querySelector(".grid > div:first-child")).then(canvas => {
            // Convert the canvas to an image and trigger download
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png'); // You can change to 'image/jpeg' or 'image/jpg'
            link.download = 'Front-ID.png'; // File name
            link.click();
        });
    }

    function exportBack() {
        // Capture the back side of the ID
        html2canvas(document.querySelector(".grid > div:last-child")).then(canvas => {
            // Convert the canvas to an image and trigger download
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png'); // You can change to 'image/jpeg' or 'image/jpg'
            link.download = 'Back-ID.png'; // File name
            link.click();
        });
    }
</script>
