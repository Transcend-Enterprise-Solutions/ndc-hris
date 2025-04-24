<div class="flex justify-center items-center flex-col w-full bg-white rounded-xl p-6 shadow-lg dark:bg-gray-800">
    <!-- Header with Dropdown and Toggle -->
    <div class="w-full mb-6 text-center relative">
        <div class="flex items-center justify-center gap-2">
            <h1 class="text-lg font-bold text-slate-800 dark:text-white">
                {{ $idType === 'virtual' ? 'Virtual ID' : 'ARTA ID' }}
            </h1>
            <button wire:click="switchIdType" type="button" class="p-2 dark:bg-slate-600">
                <i class="bi bi-arrow-repeat text-slate-800 dark:text-white"></i>
            </button>
        </div>

        <!-- Dropdown Toggle Button -->
        <div class="absolute top-0 right-0">
            <div class="relative">
                <button wire:click="toggleDropdown"
                    class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 focus:outline-none">
                    <i class="bi bi-three-dots-vertical text-slate-800 dark:text-white"></i>
                </button>

                <!-- Dropdown Menu - Context Sensitive -->
                <div wire:click.away="closeDropdown"
                    class="absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-slate-700 ring-1 ring-black ring-opacity-5 z-50 {{ $showDropdown ? 'block' : 'hidden' }}">
                    <div class="p-2">
                        @if ($idType === 'virtual')
                            <!-- Virtual ID Options -->
                            <button onclick="exportFront()"
                                class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                                Export Front ID
                            </button>
                            <button onclick="exportBack()"
                                class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                                Export Back ID
                            </button>
                            <!-- Add the new emergency contact button -->
                            <button wire:click="toggleEmergencyContactModal"
                                class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                                Add Emergency Contact
                            </button>
                        @else
                            <!-- ARTA ID Options -->
                            <button onclick="exportArtaId()"
                                class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                                Export ARTA ID
                            </button>
                        @endif

                        <!-- Common Options -->
                        <button wire:click="toggleUploadSignature"
                            class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                            Upload E-Signature
                        </button>
                        <button wire:click="toggleUploadProfilePhoto"
                            class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600 rounded-md transition-all">
                            Upload Profile Photo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Emergency Contact --}}
    <x-modal wire:model.defer="showEmergencyContactModal" centered maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Emergency Contact Information</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name of Relative</label>
                <input type="text" wire:model="emergencyContactName"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                @error('emergencyContactName')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number</label>
                <input type="text" wire:model="emergencyContactNumber"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                @error('emergencyContactNumber')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showEmergencyContactModal', false)"
                    class="px-4 py-2 text-sm text-slate-800 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600 rounded">
                    Cancel
                </button>
                <button wire:click="saveEmergencyContact"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Profile Photo Upload Modal -->
    <x-modal wire:model.defer="showProfilePhotoModal" centered maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Upload Profile Photo</h2>
            <div class="mb-4">
                <input type="file" wire:model="profilePhoto"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                @error('profilePhoto')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max file size: 2MB (PNG, JPG, JPEG)</p>
            </div>
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showProfilePhotoModal', false)"
                    class="px-4 py-2 text-sm text-slate-800 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600 rounded">
                    Cancel
                </button>
                <button wire:click="saveProfilePhoto"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    Upload
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Signature Upload Modal -->
    <x-modal wire:model.defer="showSignatureModal" centered maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Upload E-Signature</h2>
            <div class="mb-4">
                <input type="file" wire:model="signatureFile"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                @error('signatureFile')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max file size: 1MB (PNG, JPG, JPEG)</p>
            </div>
            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showSignatureModal', false)"
                    class="px-4 py-2 text-sm text-slate-800 dark:text-white hover:bg-gray-200 dark:hover:bg-slate-600 rounded">
                    Cancel
                </button>
                <button wire:click="saveSignature"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    Upload
                </button>
            </div>
        </div>
    </x-modal>

    @if ($idType === 'arta')
        <!-- ARTA ID Layout -->
        <div id="arta-id-container"
            class="sm:w-[500px] sm:h-[600px] w-[370px] h-[450px] bg-white p-6 shadow-lg border rounded-lg relative arta"
            style="background-image: url('/images/arta-bg.png'); background-size: cover; background-position: center;">
            <!-- Header -->
            <div class="flex items-center justify-center mb-6">
                <img src="/images/ndc-logo-transparent.png" class="sm:h-[64px] h-[48px]" alt="Company Logo">
                <div class="text-left">
                    <h2 class="sm:text-lg text-xs font-bold text-black">NATIONAL DEVELOPMENT COMPANY</h2>
                    <p class="sm:text-xs text-[10px] text-[#232323] -mt-1">
                        NDC Building, 116 Tordesillas St., Salcedo Village,
                    </p>
                    <p class="sm:text-xs text-[10px] text-[#232323] -mt-1">
                        Makati City, Philippines 1227
                    </p>

                </div>
            </div>

            <!-- Profile Photo -->
            <div class="flex justify-center mb-4">
                <div class="sm:w-[160px] sm:h-[160px] w-[80px] h-[80px] border border-gray-400 bg-white">
                    @if ($this->profilePhotoUrl)
                        <img src="{{ $this->profilePhotoUrl }}" alt="Profile Photo" class="w-full h-full object-cover"
                            onerror="this.onerror=null;this.innerHTML='<span class=\'text-green-500 flex items-center justify-center h-full\'>No Photo</span>';">
                    @else
                        <span class="text-green-500 flex items-center justify-center h-full">No Photo</span>
                    @endif
                </div>
            </div>

            <!-- E-Signature -->
            <div class="flex justify-center mb-4 sm:h-[48px] h-[32px]">
                @if ($this->eSignatureUrl)
                    <img src="{{ $this->eSignatureUrl }}" alt="E-Signature" class="h-full object-contain"
                        onerror="this.onerror=null;this.innerHTML='<span class=\'text-red-500 text-sm\'>SIGN HERE</span>';">
                @else
                    <span class="text-red-500 sm:text-sm text-[10px]">SIGN HERE</span>
                @endif
            </div>

            <!-- Information -->
            <div class="text-center mb-6">
                <h3 class="sm:text-xl text-[12px] font-bold text-black">{{ $name }}</h3>
                <p class="sm:text-sm text-[10px] text-center text-black tracking-tighter font-bold">
                    {{ $office_or_department }}
                </p>
                <p class="sm:text-sm text-[10px] mt-4 text-black">ID NO: <span
                        class="font-bold">{{ $emp_code }}</span></p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-2">
                <div
                    class="sm:w-[120px] sm:h-[120px] w-[80px] h-[80px] flex items-center justify-center bg-white p-1 border border-gray-200">
                    {!! $qrCode !!}
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                {{-- <p class="text-xs text-gray-500">Valid until: {{ now()->addYears(2)->format('m/d/Y') }}</p> --}}
            </div>
        </div>
    @else
        <!-- Virtual ID Layout -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Front Side -->
            <div id="virtual-id-front"
                class="sm:w-[550px] sm:h-[340px] w-[350px] h-[220px] bg-white p-4 shadow-lg border rounded-lg relative"
                style="background-image: url('/images/id-bg.png');">
                <h2 class="sm:text-2xl text-xs font-bold text-black text-left ml-8 tracking-normal">
                    {{ $name }}
                </h2>
                <p class="sm:text-sm text-[10px] text-left ml-8 text-black tracking-tighter font-bold">
                    {{ $office_or_department }}</p>

                <div class="flex items-center sm:h-[250px] h-[150px] ml-4">
                    <div class="flex flex-col items-center space-y-1">
                        <!-- Picture Box -->
                        <div
                            class="sm:w-[160px] sm:h-[160px] w-[80px] h-[80px] border border-gray-400 flex items-center justify-center bg-white mt-2">
                            @if ($this->profilePhotoUrl)
                                <img src="{{ $this->profilePhotoUrl }}" alt="Profile Photo"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null;this.innerHTML='<span class=\'text-green-500\'>Picture</span>';">
                            @else
                                <span class="text-green-500">Picture</span>
                            @endif
                        </div>

                        <!-- SIGN HERE -->
                        @if ($this->eSignatureUrl)
                            <div class="flex items-center justify-center sm:h-[48px] h-[32px]">
                                <img src="{{ $this->eSignatureUrl }}" alt="E-Signature"
                                    class="max-w-full max-h-full object-contain"
                                    onerror="this.onerror=null;this.parentElement.innerHTML='<p class=\'text-red-500 text-sm\'>SIGN HERE</p>';">
                            </div>
                        @else
                            <div class="flex items-center justify-center sm:h-[48px] h-[32px]">
                                <p class="text-red-500 sm:text-sm text-[10px]">SIGN HERE</p>
                            </div>
                        @endif

                        <!-- ID Number -->
                        <p class="sm:text-sm text-[10px] text-black">ID NO. <span
                                class="underline">{{ $emp_code }}</span></p>
                    </div>
                </div>

                <div
                    class="absolute sm:top-[230px] sm:right-7 transform sm:-translate-y-1/2 top-[150px] right-3 -translate-y-1/2 flex flex-col items-center text-center">
                    <img src="/images/ndc-logo-transparent.png" class="sm:h-[120px] h-[60px]" alt="">
                    <p class="sm:text-xs text-[8px] text-black -mt-2">
                        NDC Building, 116 Tordesillas St.,<br> Salcedo Village, Makati City, Philippines 1227
                    </p>
                    <p class="sm:text-xs text-[8px] text-black">T (632) 8840-4838 to 62</p>
                </div>
            </div>

            <!-- Back Side -->
            <div id="virtual-id-back"
                class="sm:w-[550px] sm:h-[340px] w-[350px] h-[220px] bg-white sm:p-[16px] p-[8px] shadow-lg border rounded-lg relative"
                style="background-image: url('/images/id-bg.png');">
                <div class="flex justify-between items-center m-4">
                    <div class="w-[70%]">
                        <h2 class="sm:text-sm text-[9px] font-bold text-black">IN CASE OF EMERGENCY, PLEASE NOTIFY:
                        </h2>
                        <p class="sm:text-sm text-[9px] font-bold text-black">NAME: <span
                                class="font-normal">{{ $emergencyContactName ?? 'EMPLOYEE RELATIVE' }}</span></p>
                        <p class="sm:text-sm text-[9px] font-bold text-black">TEL. NO.: <span
                                class="font-normal">{{ $emergencyContactNumber ?? '09123456789' }}</span>
                        </p>
                    </div>

                    <div class="w-[30%] flex items-center justify-center">
                        <div class="sm:w-[96px] sm:h-[96px] w-[56px] h-[56px] flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center m-4">
                    <div class="w-[65%] sm:text-sm text-[9px]">
                        <p class="text-black text-justify tracking-tight leading-none">
                            This certifies that the person whose name, picture, and signature appear on this card is an
                            employee of the <span class="font-bold">National Development Company.</span>
                        </p>
                    </div>

                    <div class="w-[30%] flex items-center justify-center space-x-2">
                        <div class="sm:w-[60px] sm:h-[60px] w-[35px] h-[35px] flex items-center justify-center">
                            <img src="/images/dti-logo.png" alt="dti-logo">
                        </div>
                        <div class="sm:w-[55px] sm:h-[55px] w-[30px] h-[30px] flex items-center justify-center">
                            <img src="/images/tuv.png" alt="tuv-logo">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center m-4">
                    <div class="w-[65%] sm:text-sm text-[9px]">
                        <p class=" text-black text-justify tracking-tight leading-none">
                            Report loss of card to the Human Resources Unit for immediate replacement. Finder of this is
                            requested to return it to the National Development Company or call (02) 8840-4838.
                        </p>
                    </div>

                    <div class="w-[35%] flex flex-col items-center text-center justify-center space-x-2">
                        <p class="sm:text-[11px] text-[8px] text-red-500 font-bold">SIGN</p>
                        <p class="sm:text-[11px] text-[7px] text-black font-bold text-nowrap">Atty. RHOEL Z. MABAZZA
                        </p>
                        <p class="sm:text-[10px] text-[7px] text-black font-bold text-nowrap">Assistant General Manager
                        </p>
                        <p class="sm:text-[10px] text-[7px] text-black font-bold text-nowrap">Corporate Support Group
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function exportFront() {
        if ("{{ $idType }}" !== 'virtual') return;

        const element = document.getElementById('virtual-id-front');
        if (!element) {
            console.error("Front ID container not found!");
            return;
        }

        html2canvas(element, {
            scale: 2, // Higher quality
            logging: true, // Helpful for debugging
            useCORS: true // For external images
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png', 1.0); // Highest quality
            link.download = 'Front-ID.png';
            link.click();
        }).catch(err => {
            console.error("Error generating front ID:", err);
        });
    }

    function exportBack() {
        if ("{{ $idType }}" !== 'virtual') return;

        const element = document.getElementById('virtual-id-back');
        if (!element) {
            console.error("Back ID container not found!");
            return;
        }

        html2canvas(element, {
            scale: 2,
            logging: true,
            useCORS: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png', 1.0);
            link.download = 'Back-ID.png';
            link.click();
        }).catch(err => {
            console.error("Error generating back ID:", err);
        });
    }

    function exportArtaId() {
        const element = document.getElementById('arta-id-container');
        if (!element) {
            console.error("ARTA ID container not found!");
            return;
        }

        html2canvas(element).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = 'ARTA-ID.png';
            link.click();
        }).catch(err => {
            console.error("Error generating ARTA ID:", err);
        });
    }
</script>
