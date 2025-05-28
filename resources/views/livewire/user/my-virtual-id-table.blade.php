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
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Upload E-Signature</h2>
            <p class="italic mb-4">(Please upload no background image)</p>
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
        <div id="arta-id-container" class="w-[500px] h-[600px] bg-white p-6 shadow-lg border rounded-lg relative arta"
            style="background-image: url('/images/arta-bg-darker.png'); background-size: cover; background-position: center;">
            <!-- Header -->
            <div class="flex items-center justify-center mb-6">
                <img src="/images/ndc-logo-transparent.png" class="h-16" alt="Company Logo">
                <div class="text-left">
                    <h2 class="text-md font-bold text-black" style="font-family: 'Arial Black', Gadget, sans-serif;">
                        NATIONAL
                        DEVELOPMENT COMPANY</h2>
                    <p class="text-xs text-black -mt-1" style="font-family: 'Arial Black', Gadget, sans-serif;">
                        NDC Building, 116 Tordesillas St., Salcedo Village,
                    </p>
                    <p class="text-xs text-black -mt-1" style="font-family: 'Arial Black', Gadget, sans-serif;">
                        Makati City, Philippines 1227
                    </p>
                </div>
            </div>

            <!-- Profile Photo - Clickable when empty (maintaining original w-40 h-40 size) -->
            <div class="flex justify-center mb-4">
                <div class="w-40 h-40 border border-gray-400 bg-white flex items-center justify-center relative"
                    @if (!$this->profilePhotoUrl) wire:click="toggleUploadProfilePhoto" @endif>
                    @if ($this->profilePhotoUrl)
                        <img src="{{ $this->profilePhotoUrl }}" alt="Profile Photo" class="w-full h-full object-cover"
                            onerror="this.onerror=null;this.innerHTML='<span class=\'text-green-500 flex items-center justify-center h-full cursor-pointer\'>Click to Upload</span>';">
                    @else
                        <span class="text-green-500 flex flex-col items-center justify-center h-full cursor-pointer">
                            <i class="bi bi-camera text-2xl mb-2"></i>
                            <span class="text-sm">Upload Photo</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- E-Signature - Clickable when empty (maintaining original 40px height) -->
            <div class="flex justify-center mb-4" style="height: 40px;">
                @if ($this->eSignatureUrl)
                    <img src="{{ $this->eSignatureUrl }}" alt="E-Signature" class="h-full object-contain"
                        onerror="this.onerror=null;this.innerHTML='<span class=\'text-red-500 text-sm flex items-center cursor-pointer\'><i class=\'bi bi-pen-fill mr-2\'></i> Upload Signature</span>';">
                @else
                    <div class="flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors duration-200 h-full"
                        wire:click="toggleUploadSignature">
                        <span class="text-red-500 text-sm flex items-center">
                            <i class="bi bi-pen-fill mr-2"></i> Upload Signature
                        </span>
                    </div>
                @endif
            </div>

            <!-- Information -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-black">{{ $name }}</h3>
                <p class="text-sm text-center text-black tracking-tighter font-bold">
                    {{ $office_or_department }}
                </p>
                <p class="text-sm mt-4 text-black">ID NO: <span class="font-bold">{{ $emp_code }}</span></p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-2">
                <div class="flex items-center justify-center bg-white p-1 border border-gray-200">
                    {!! $qrCode !!}
                </div>
            </div>
        </div>
    @else
        <!-- Virtual ID Layout -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Front Side -->
            <div id="virtual-id-front" class="w-[550px] h-[340px] bg-white p-4 shadow-lg border rounded-lg relative "
                style="background-image: url('/images/id-bg-darker.png');">
                <h2 class="text-2xl text-black text-left ml-8 uppercase"
                    style="font-family: 'Arial Black', Gadget, sans-serif;">
                    {{ $name }}
                </h2>

                <p class="text-sm text-left ml-8 text-black tracking-tighter font-bold"
                    style="font-family: 'Arial', sans-serif;">

                    {{ $office_or_department }}</p>

                <div class="flex items-center h-[250px] ml-4">
                    <div class="flex flex-col items-center space-y-1">
                        <!-- Picture Box - Clickable when empty (maintaining original size) -->
                        <div class="w-40 h-40 border border-gray-400 bg-white flex items-center justify-center mt-2 relative"
                            @if (!$this->profilePhotoUrl) wire:click="toggleUploadProfilePhoto" @endif>
                            @if ($this->profilePhotoUrl)
                                <img src="{{ $this->profilePhotoUrl }}" alt="Profile Photo"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null;this.innerHTML='<span class=\'text-green-500 flex items-center justify-center h-full cursor-pointer\'>Click to Upload</span>';">
                            @else
                                <span
                                    class="text-green-500 flex flex-col items-center justify-center h-full cursor-pointer">
                                    <i class="bi bi-camera text-2xl mb-2"></i>
                                    <span class="text-sm">Upload Photo</span>
                                </span>
                            @endif
                        </div>

                        <!-- SIGN HERE - Clickable when empty (maintaining original height) -->
                        <div class="w-full flex justify-center" style="height: 48px;">
                            @if ($this->eSignatureUrl)
                                <img src="{{ $this->eSignatureUrl }}" alt="E-Signature"
                                    class="h-full object-contain"
                                    onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'w-full flex justify-center items-center cursor-pointer hover:bg-gray-50 transition-colors duration-200\' style=\'height: 48px;\' wire:click=\'toggleUploadSignature\'><span class=\'text-red-500 text-sm flex items-center\'><i class=\'bi bi-pen-fill mr-2\'></i> Upload Signature</span></div>';">
                            @else
                                <div class="flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors duration-200 w-full h-full"
                                    wire:click="toggleUploadSignature">
                                    <span class="text-red-500 text-sm flex items-center">
                                        <i class="bi bi-pen-fill mr-2"></i> Upload Signature
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- ID Number -->
                        <p class="text-sm text-black">ID No. {{ $emp_code }}</p>
                    </div>
                </div>

                <div
                    class="absolute top-[230px] right-7 transform -translate-y-1/2 flex flex-col items-center text-center">
                    <img src="/images/ndc-logo-transparent.png" class="h-[120px]" alt="">
                    <p class="text-xs text-black -mt-2">
                        NDC Building, 116 Tordesillas St.,<br> Salcedo Village, Makati City, Philippines 1227
                    </p>
                    <p class="text-xs text-black">T (632) 8840-4838 to 62</p>
                </div>
            </div>

            <!-- Back Side -->
            <div id="virtual-id-back" class="w-[550px] h-[340px] bg-white p-4 shadow-lg border rounded-lg relative"
                style="background-image: url('/images/id-bg-darker.png');">
                <div class="flex justify-between items-center m-4">
                    <div class="w-[70%]">
                        <h2 class="text-sm text-black" style="font-family: 'Arial Black', Gadget, sans-serif;">IN CASE
                            OF
                            EMERGENCY, PLEASE NOTIFY:
                        </h2>
                        <p class="text-sm font-bold text-black">NAME: <span
                                style="font-family: 'Arial Black', Gadget, sans-serif;">{{ $emergencyContactName ?? 'EMPLOYEE RELATIVE' }}</span>
                        </p>
                        <p class="text-sm font-bold text-black">TEL. NO.: <span
                                style="font-family: 'Arial Black', Gadget, sans-serif;">{{ $emergencyContactNumber ?? '09123456789' }}</span>
                        </p>
                    </div>

                    <div class="w-[30%] flex items-center justify-center">
                        <div class="w-24 h-24 flex items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center m-4">
                    <div class="w-[65%] text-sm">
                        <p class="text-black text-justify font-bold tracking-tight leading-none">
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
                    <div class="w-[65%] text-sm">
                        <p class=" text-black text-justify font-bold tracking-tight leading-none">
                            Report loss of card to the Human Resources Unit for immediate replacement. Finder of this is
                            requested to return it to the National Development Company or call (02) 8840-4838.
                        </p>
                    </div>

                    <div class="w-[35%] flex flex-col items-center text-center justify-center space-x-2">
                        <p class="text-[11px] text-red-500 font-bold">SIGN</p>
                        <p class="text-[11px] text-black font-bold text-nowrap">Atty. RHOEL Z. MABAZZA
                        </p>
                        <p class="text-[10px] text-black font-bold text-nowrap">Assistant General Manager
                        </p>
                        <p class="text-[10px] text-black font-bold text-nowrap">Corporate Support Group
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
            scale: 2,
            logging: true,
            useCORS: true,
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png', 1.0);
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
            useCORS: true,
            backgroundColor: null
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

        html2canvas(element, {
            scale: 2,
            logging: true,
            useCORS: true,
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png', 1.0);
            link.download = 'ARTA-ID.png';
            link.click();
        }).catch(err => {
            console.error("Error generating ARTA ID:", err);
        });
    }
</script>
