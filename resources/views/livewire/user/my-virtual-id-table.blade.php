<div class="w-full">
    <div class="flex flex-col justify-center w-full bg-white rounded-xl p-6 shadow-lg dark:bg-gray-800">
        <div class="pb-4 pt-4 sm:pt-1">
            <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">VIRTUAL ID</h1>
        </div>

        <button wire:click="downloadPdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded p-1">
            Print ID Card
        </button>

        <div class="flex flex-col md:flex-row justify-evenly gap-4 w-full bg-white rounded-xl p-6 dark:bg-gray-800">
            <!-- Virtual ID Card Front -->
            <div class="flex flex-col items-center p-4 w-full max-w-lg h-48 bg-slate-200 dark:bg-slate-200 rounded-lg bg-cover bg-center md:h-54 md:max-w-lg sm:h-72 sm:max-w-sm"
                style="background-image: url('/images/Vector.png');">
                <!-- ID Card Header -->
                <div class="flex items-center justify-evenly w-full sm:justify-center">
                    <img src="/images/ndc_logo.png" class="h-4 sm:h-12" alt="">
                    <h2 class="text-xs font-bold whitespace-nowrap sm:text-xl md:text-md">
                        <span class="text-blue-500">NATIONAL</span>
                        <span class="text-green-500">DEVELOPMENT</span>
                        <span class="text-orange-500">COMPANY</span>
                    </h2>
                </div>
                <!-- Profile Photo -->
                <div class="flex justify-evenly items-center w-full">
                    <div class="mt-4 mb-4 flex flex-col justify-center items-center text-black">
                        <img src="{{ $profilePhotoPath ? asset('storage/' . $profilePhotoPath) : asset('default-avatar.png') }}"
                            alt="Profile Photo"
                            class="w-20 h-20 sm:w-28 sm:h-28 border-2 border-white shadow-md object-cover">
                        <p class="text-[10px] sm:text-xs mt-1">{{ $position }}</p>
                    </div>
                    <div class="flex flex-col items-center text-black">
                        <!-- Personal Details -->
                        <div class="text-black text-left w-full text-[10px] sm:text-sm">
                            <p><strong>Name:</strong> {{ $name }}</p>
                            <p><strong>Employee Code:</strong> {{ $emp_code }}</p>
                            <p><strong>Date of Birth:</strong> {{ $dateOfBirth }}</p>
                            <p><strong>Place of Birth:</strong> {{ $placeOfBirth }}</p>
                            <p><strong>Sex:</strong> {{ $sex }}</p>
                            <p><strong>Civil Status:</strong> {{ $civilStatus }}</p>
                            <p><strong>Blood Type:</strong> {{ $bloodType }}</p>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <div class="w-full flex justify-center items-center text-white text-[10px] sm:text-xs">
                    <p class="text-center sm:mt-4">Issued by NDC</p>
                </div>
            </div>

            <!-- Virtual ID Card Back -->
            <div class="flex flex-col p-4 w-full max-w-lg h-48 bg-slate-200 dark:bg-slate-200 rounded-lg bg-cover bg-center md:h-54 md:max-w-lg sm:h-72 sm:max-w-sm"
                style="background-image: url('/images/Vector.png');">
                <!-- QR Code Display -->
                <div class="flex justify-center items-center ml-4 sm:mt-4">
                    <div class="flex justify-center items-start text-black">
                        <!-- Terms and Conditions -->
                        <div class="text-black text-left w-full text-[10px] sm:text-sm">
                            <h3 class="font-bold mb-2 ">TERMS AND CONDITIONS:</h3>
                            <ul class="list-disc pl-4 leading-5">
                                <li>This ID is valid for official purposes only.</li>
                                <li>Any misuse of this ID is subject to legal consequences.</li>
                                <li>Please contact support for any issues.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="sm:w-40 sm:h-32 w-28 h-24 overflow-hidden">
                        <div class="w-full h-full flex p-1 items-center justify-center">
                            {!! $qrCode !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
