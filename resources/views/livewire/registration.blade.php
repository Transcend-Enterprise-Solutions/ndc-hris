<section>
    <div class="px-8 py-24 mx-auto md:px-12 lg:px-32 max-w-7xl">
        <div class="max-w-lg mx-auto md:max-w-xl md:w-full">
            <div class="flex flex-col text-center">
                <h1 class="text-3xl font-semibold tracking-tight text-gray-900">
                    Registration Form
                </h1>
                <p class="mt-4 text-base font-medium text-gray-500"></p>
            </div>
            <div class="p-2 mt-8 border bg-gray-50 rounded-3xl">
                <div x-data="{ step: 1, formData: { firstname: '', middlename: '', surname: '', citizenship: '', height: '', weight: '', bloodtype: '', gsis: '', pagibig: '', philhealth: '', sss: '', tin: '', agency: '', username: '', password: '' } }"
                    class="p-10 bg-white border shadow-lg rounded-2xl">
                    <!-- Step 1 -->
                    <div x-show="step === 1">
                        <h2 class="text-lg font-medium text-gray-500">
                            Step 1 out of 8: Personal Information
                        </h2>

                        <div class="mt-12 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">First Name</label>
                                <input type="text" id="name" x-model="formData.firstname"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your firstname">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Surname</label>
                                <input type="text" id="name" x-model="formData.middlename"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your surname">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Middle Name</label>
                                <input type="text" id="name" x-model="formData.surname"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your middlename">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Sex at Birth</label>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                                    data-dropdown-trigger="click"
                                    class="w-full text-zinc-300 bg-white font-medium rounded-lg text-sm px-5 border py-3.4 text-center inline-flex items-center dark:focus:ring-zinc-300 border-zinc-300 focus:outline-none focus:ring-zinc-300"
                                    type="button">Select one <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Male</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Female</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <label for="name" class="block text-sm text-gray-700">Birth Date</label>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker1" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select date">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Citizenship</label>
                                <input type="text" id="name" x-model="formData.citizenship"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your citizenship">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-3">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Height (m)</label>
                                <input type="text" id="name" x-model="formData.height"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Ex: 0m">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Weight (kg)</label>
                                <input type="text" id="name" x-model="formData.weight"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Ex: 0kg">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Blood type</label>
                                <input type="text" id="name" x-model="formData.bloodtype"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-12">
                            <button
                                class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                @click="step++">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div x-show="step === 2" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 2 out of 8: Government IDs
                        </h2>
                        <div class="mt-12 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">GSIS ID No.</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">PAGIBIG ID No.</label>
                                <input type="text" id="name" x-model="formData.pagibig"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">PhilHealth ID No.</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">SSS No.</label>
                                <input type="text" id="name" x-model="formData.sss"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">TIN No.</label>
                                <input type="text" id="name" x-model="formData.tin"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Agency Employee No.</label>
                                <input type="text" id="name" x-model="formData.agency"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div x-show="step === 3" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 3 out of 8: Contact Information
                        </h2>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                            <legend class="text-black">Permanent Address</legend>
                            <div class="mt-2">
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Region</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Province</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">City</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Barangay</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">House Number | Street |
                                        Subdivision</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                            <legend class="text-black">Residential Address</legend>
                            <div class="mt-2">
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Region</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Province</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">City</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">Barangay</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full mt-2">
                                    <label for="name" class="block text-sm text-gray-700">House Number | Street |
                                        Subdivision</label>
                                    <input type="text" id="name" x-model="formData.gsis"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Telephone No.</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Mobile No.</label>
                                <input type="text" id="name" x-model="formData.sss"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Email Address</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div x-show="step === 4" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 4 out of 8: Family Information
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Name</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <label for="name" class="block text-sm text-gray-700">Spouse Birth Date</label>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker2" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select birthdate">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Occupation</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Employer</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Children's Name</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>

                            <label for="name" class="block text-sm text-gray-700">Children's Birth Date</label>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker3" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select birth date">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Father's Name</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Mother's Maiden Name</label>
                                <input type="text" id="name" x-model="formData.gsis"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Step 5 -->
                    <div x-show="step === 5" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 5 out of 8: Educational Background
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Highest Educational
                                    Attainment</label>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown1"
                                    data-dropdown-trigger="click"
                                    class="w-full text-zinc-300 bg-white font-medium rounded-lg text-sm px-5 border py-3.4 text-center inline-flex items-center dark:focus:ring-zinc-300 border-zinc-300 focus:outline-none focus:ring-zinc-300"
                                    type="button">Select one <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown1"
                                    class="z-11 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Primary</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Intermediate</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Vocational</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">College</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Graduate
                                                Studies</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Name of School</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Degree</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Period of Attendance</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Year Graduated</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div x-show="step === 6" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 6 out of 8: Eligibility
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Rating</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <label for="name" class="block text-sm text-gray-700">Date of Examination</label>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker4" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select date">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Place of Examination</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">License</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div x-show="step === 7" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 7 out of 8: Work Experience
                        </h2>

                        <div class="mt-12 gap-2 columns-2">
                            <label for="name" class="block text-sm text-gray-700">Inclusive Dates</label>
                            <div class="relative max-w-sm">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input datepicker id="default-datepicker5" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select date">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Position Title</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Department</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Monthly Salary</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Status of Appointment</label>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown2"
                                    data-dropdown-trigger="click"
                                    class="w-full text-zinc-300 bg-white font-medium rounded-lg text-sm px-5 border py-3.4 text-center inline-flex items-center dark:focus:ring-zinc-300 border-zinc-300 focus:outline-none focus:ring-zinc-300"
                                    type="button">Select one <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdown2"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownDefaultButton">
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Government
                                                Service</a>
                                        </li>
                                        <li>
                                            <a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Not</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 8 -->
                    <div x-show="step === 8" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 8 out of 8: Other Relevant Information
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Voluntary Works</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4 mt-4">
                            <legend class="text-black">Learning and Development</legend>
                            <div class="mt-2">

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Title of Training</label>
                                        <input type="text" id="name" x-model="formData.philhealth"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>

                                    <label for="name" class="block text-sm text-gray-700">Inclusive Dates</label>
                                    <div class="relative max-w-sm">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-white-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input datepicker id="default-datepicker5" type="text"
                                            class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                            placeholder="Select date">
                                    </div>
                                </div>

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Number of hours</label>
                                        <input type="text" id="name" x-model="formData.philhealth"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Conducted by</label>
                                        <input type="text" id="name" x-model="formData.philhealth"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Special Skills and Hobbies</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Non-academic Distinctions</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Membership in
                                    associations/organizations</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Character References</label>
                                <input type="text" id="name" x-model="formData.philhealth"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium bg-gray-100 rounded-xl hover:bg-gray-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="step--">
                                    Previous
                                </button>
                            </div>
                            <div class="w-full">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-gray-900 rounded-xl hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Ends component -->
    </div>
</section>