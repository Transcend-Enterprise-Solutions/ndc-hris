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
                <div class="p-10 bg-white border shadow-lg rounded-2xl">
                    <!-- Step 1 -->
                    @if ($step === 1)
                        <div>
                            <h2 class="text-lg font-medium text-gray-500">
                                Step 1 out of 8: <span class="font-bold text-black">Personal Information</span>
                            </h2>

                            <div class="mt-12 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="firstname" class=" text-sm text-gray-700">First Name</label>
                                    <input type="text" id="first_name" wire:model="first_name"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your firstname">
                                    @error('first_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="middlename" class=" text-sm text-gray-700">Middle Name</label>
                                    <input type="text" id="middle_name" wire:model="middle_name"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your middlename">
                                    @error('middle_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="surname" class=" text-sm text-gray-700">Surname</label>
                                    <input type="text" id="surname" wire:model="surname"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your surname">
                                    @error('surname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="suffix" class=" text-sm text-gray-700">Suffix</label>
                                    <select id="suffix" wire:model="suffix"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                        <option value="">None</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                    </select>
                                    @error('suffix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="sex" class=" text-sm text-gray-700">Sex at Birth</label>
                                    <select id="sex" wire:model="sex"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                        <option value="">Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @error('sex') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="w-full">
                                    <label for="date_of_birth" class=" text-sm text-gray-700">Birth Date</label>
                                    <input type="date" id="date_of_birth" wire:model="date_of_birth"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Select date">
                                    @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="citizenship" class=" text-sm text-gray-700">Citizenship</label>
                                    <input type="text" id="citizenship" wire:model="citizenship"
                                        class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your citizenship">
                                    @error('citizenship') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-full">
                                    <label for="civil_status" class=" text-sm text-gray-700">Civil Status</label>
                                    <select id="civil_status" wire:model="civil_status"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                        <option value="">Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Separated">Separated</option>
                                        <option value="Annulled">Annulled</option>
                                        <option value="Live-in">Live-in</option>
                                        <option value="Unknown">Unknown</option>
                                    </select>
                                    @error('civil_status') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-3">
                                <div class="w-full">
                                    <label for="height" class=" text-sm text-gray-700">Height (m)</label>
                                    <input type="text" id="height" wire:model="height"
                                        class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your height in meters">
                                    @error('height') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="weight" class=" text-sm text-gray-700">Weight (kg)</label>
                                    <input type="text" id="weight" wire:model="weight"
                                        class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your weight in kilograms">
                                    @error('weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="blood_type" class=" text-sm text-gray-700">Blood type</label>
                                    <input type="text" id="blood_type" wire:model="blood_type"
                                        class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Enter your blood type">
                                    @error('blood_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-12">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="toStep2">
                                    Next
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Step 2 -->
                    @if ($step === 2)
                        <div>
                            <h2 class="mb-4 text-lg font-medium text-gray-500">
                                Step 2 out of 8: <span class="font-bold text-black">Government IDs</span>
                            </h2>

                            <div class="mt-12 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">GSIS ID No.</label>
                                    <input type="text" id="gsis" wire:model="gsis"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('gsis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">PAGIBIG ID No.</label>
                                    <input type="text" id="pagibig" wire:model="pagibig"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('pagibig') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">PhilHealth ID No.</label>
                                    <input type="text" id="philhealth" wire:model="philhealth"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('philhealth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">SSS No.</label>
                                    <input type="text" id="sss" wire:model="sss"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('sss') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">TIN No.</label>
                                    <input type="text" id="tin" wire:model="tin"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('tin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-full">
                                    <label for="name" class="text-sm text-gray-700">Agency Employee No.</label>
                                    <input type="text" id="agency" wire:model="agency"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    @error('agency') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex gap-2 mt-12 columns-2">
                                <div class="w-full">
                                    <button
                                        class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                        wire:click="prevStep">
                                        Previous
                                    </button>
                                </div>
                                <div class="w-full">
                                    <button
                                        class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                        wire:click="toStep3">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 3 -->
                    @if ($step === 3)
                        <div>
                            <h2 class="mb-4 text-lg font-medium text-gray-500">
                                Step 3 out of 8: <span class="font-bold text-black">Contact Information</span>
                            </h2>

                            <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                                <legend class="text-black">Permanent Address</legend>
                                <div class="mt-2">
                                    <div class="w-full mt-2">
                                        <label for="permanent_region" class="block text-sm text-gray-700">Region <span
                                                class="required-mark">*</span></label>
                                        <select
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            wire:model.live="permanent_selectedRegion" id="permanent_region"
                                            name="permanent_selectedRegion" required>
                                            @if ($regions)
                                                <option class="text-base text-gray-700 capitalize block mb-1.5" value=""
                                                    style="opacity: .6;">Select Region</option>
                                                @foreach ($regions->sortBy('region_description') as $region)
                                                <option class="text-base text-gray-700 capitalize block mb-1.5"
                                                    value="{{ $region->region_description }}">{{
                                                    $region->region_description }}</option>
                                                @endforeach
                                            @else
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">No
                                                region available</option>
                                            @endif
                                        </select>
                                        @error('permanent_selectedRegion') <span class="text-red-500 text-sm">The Region
                                            Field is required</span> @enderror
                                    </div>

                                    <div class="w-full mt-2">
                                        <label for="permanent_province" class="block text-sm text-gray-700">Province <span
                                                class="required-mark">*</span></label>
                                        <select
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            wire:model.live="permanent_selectedProvince" id="permanent_province"
                                            name="permanent_selectedProvince" required>
                                            @if ($pprovinces)
                                                <option class="text-base text-gray-700 capitalize block mb-1.5" value=""
                                                    style="opacity: .6;">Select Province</option>
                                                @foreach ($pprovinces->sortBy('province_description') as $province)
                                                    <option class="text-base text-gray-700 capitalize block mb-1.5"
                                                        value="{{ $province->province_description }}">{{
                                                        $province->province_description }}</option>
                                                @endforeach
                                            @else
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">Select
                                                a region</option>
                                            @endif
                                        </select>
                                        @error('permanent_selectedProvince') <span class="text-red-500 text-sm">The Province
                                            Field is required</span> @enderror
                                    </div>

                                    <div class="w-full mt-2">
                                        <label for="permanent_city" class="block text-sm text-gray-700">City <span
                                                class="required-mark">*</span></label>
                                        <select
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            wire:model="permanent_selectedCity" id="permanent_city"
                                            name="permanent_selectedCity" required>
                                            @if($pcities)
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">Select
                                                City</option>
                                            @foreach ($pcities as $city)
                                            <option class="text-base text-gray-700 capitalize block mb-1.5"
                                                value="{{ $city->city_municipality_description }}">{{
                                                $city->city_municipality_description }}</option>
                                            @endforeach
                                            @else
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">Select
                                                a province</option>
                                            @endif
                                        </select>
                                        @error('permanent_selectedCity') <span class="text-red-500 text-sm">The City field
                                            is required</span> @enderror
                                    </div>

                                    <div class="w-full mt-2">
                                        <label for="permanent_barangay" class="block text-sm text-gray-700">Barangay <span
                                                class="required-mark">*</span></label>
                                        <select
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            wire:model="permanent_selectedBarangay" id="permanent_barangay"
                                            name="permanent_selectedBarangay" required>
                                            @if($pbarangays)
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">Select
                                                Barangay</option>
                                            @foreach ($pbarangays as $barangay)
                                            <option class="text-base text-gray-700 capitalize block mb-1.5"
                                                value="{{ $barangay->barangay_description }}">{{
                                                $barangay->barangay_description }}</option>
                                            @endforeach
                                            @else
                                            <option class="text-base text-gray-700 capitalize block mb-1.5" value="">Select
                                                a city</option>
                                            @endif
                                        </select>
                                        @error('permanent_selectedBarangay') <span class="text-red-500 text-sm">The Barangay
                                            field is required</span> @enderror
                                    </div>

                                    <div class="w-full mt-2">
                                        <label for="p_house_street" class="block text-sm text-gray-700">House Number |
                                            Street | Subdivision <span class="required-mark">*</span></label>
                                        <input type="text" id="p_house_street" wire:model="p_house_street"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            name="p_house_street" required>
                                        @error('p_house_street') <span class="text-red-500 text-sm">The Street and Barangay
                                            field is required</span> @enderror
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                                <legend class="text-black">Residential Address</legend>
                                <div class="mt-2">
                                    <div class="w-full mt-2">
                                        <label for="name" class="block text-sm text-gray-700">Region</label>
                                        <input type="text" id="name" x-model="formData.rregion"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full mt-2">
                                        <label for="name" class="block text-sm text-gray-700">Province</label>
                                        <input type="text" id="name" x-model="formData.rprovince"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full mt-2">
                                        <label for="name" class="block text-sm text-gray-700">City</label>
                                        <input type="text" id="name" x-model="formData.rcity"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full mt-2">
                                        <label for="name" class="block text-sm text-gray-700">Barangay</label>
                                        <input type="text" id="name" x-model="formData.rbarangay"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full mt-2">
                                        <label for="name" class="block text-sm text-gray-700">House Number | Street |
                                            Subdivision</label>
                                        <input type="text" id="name" x-model="formData.rstreet"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                </div>
                            </fieldset>

                            <div class="mt-4 gap-2 columns-2">
                                <div class="w-full">
                                    <label for="name" class="block text-sm text-gray-700">Telephone No.</label>
                                    <input type="text" id="name" x-model="formData.tel"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div class="w-full">
                                    <label for="name" class="block text-sm text-gray-700">Mobile No.</label>
                                    <input type="text" id="name" x-model="formData.mob"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                            </div>

                            <div class="mt-4 gap-2 columns-1">
                                <div class="w-full">
                                    <label for="name" class="block text-sm text-gray-700">Email Address</label>
                                    <input type="text" id="name" x-model="formData.email"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                            </div>

                            <div class="flex gap-2 mt-12 columns-2">
                                <div class="w-full">
                                    <button
                                        class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                        wire:click="prevStep">
                                        Previous
                                    </button>
                                </div>
                                <div class="w-full">
                                    <button
                                        class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                        wire:click="toStep4">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 4 -->
                    <div x-show="step === 4" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 4 out of 8: <span class="font-bold text-black">Family Information</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Name</label>
                                <input type="text" id="name" x-model="formData.spouse_name"
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
                                <input datepicker id="spouse_birthdate" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select birthdate">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Occupation</label>
                                <input type="text" id="name" x-model="formData.spouse_occupation"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Spouse Employer</label>
                                <input type="text" id="name" x-model="formData.spouse_employer"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Children's Name</label>
                                <input type="text" id="name" x-model="formData.childrens_name"
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
                                <input datepicker id="childrens_birthdate" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select birth date">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Father's Name</label>
                                <input type="text" id="name" x-model="formData.fathers_name"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Mother's Maiden Name</label>
                                <input type="text" id="name" x-model="formData.mothers_name"
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div x-show="step === 5" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 5 out of 8: <span class="font-bold text-black">Educational Background</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Highest Educational
                                    Attainment</label>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="educational_attainment"
                                    data-dropdown-trigger="click"
                                    class="w-full text-zinc-300 bg-white font-medium rounded-lg text-sm px-5 border py-3.4 text-center inline-flex items-center dark:focus:ring-zinc-300 border-zinc-300 focus:outline-none focus:ring-zinc-300"
                                    type="button">Select one <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="educational_attainment"
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
                                <input type="text" id="name" x-model="formData.name_of_school"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Degree</label>
                                <input type="text" id="name" x-model="formData.degree"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Period of Attendance</label>
                                <input type="text" id="name" x-model="formData.attendance"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Year Graduated</label>
                                <input type="text" id="name" x-model="formData.year_graduated"
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6 -->
                    <div x-show="step === 6" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 6 out of 8: <span class="font-bold text-black">Eligibility</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Rating</label>
                                <input type="text" id="name" x-model="formData.rating"
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
                                <input datepicker id="exam_date" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select date">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Place of Examination</label>
                                <input type="text" id="name" x-model="formData.exam_loc"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">License</label>
                                <input type="text" id="name" x-model="formData.license"
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div x-show="step === 7" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 7 out of 8: <span class="font-bold text-black">Work Experience</span>
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
                                <input datepicker id="inclusive_date1" type="text"
                                    class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                    placeholder="Select date">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Position Title</label>
                                <input type="text" id="name" x-model="formData.position_title"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Department</label>
                                <input type="text" id="name" x-model="formData.department"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>

                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Monthly Salary</label>
                                <input type="text" id="name" x-model="formData.monthly_salary"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Status of Appointment</label>
                                <button id="dropdownDefaultButton" data-dropdown-toggle="appointment_status"
                                    data-dropdown-trigger="click"
                                    class="w-full text-zinc-300 bg-white font-medium rounded-lg text-sm px-5 border py-3.4 text-center inline-flex items-center dark:focus:ring-zinc-300 border-zinc-300 focus:outline-none focus:ring-zinc-300"
                                    type="button">Select one <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="appointment_status"
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    @click="step++">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 8 -->
                    <div x-show="step === 8" style="display: none;">
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 8 out of 8: <span class="font-bold text-black">Other Relevant Information</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Voluntary Works</label>
                                <input type="text" id="name" x-model="formData.voluntary_works"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4 mt-4">
                            <legend class="text-black">Learning and Development</legend>
                            <div class="mt-2">

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Title of Training</label>
                                        <input type="text" id="name" x-model="formData.training_title"
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
                                        <input datepicker id="inclusive_date2" type="text"
                                            class="bg-zinc-300 text-black text-sm rounded-lg block w-full ps-10 py-3.4 dark:bg-white border-zinc-300 dark:placeholder-zinc-300 dark:text-black focus:border-zinc-300 focus:ring-zinc-300"
                                            placeholder="Select date">
                                    </div>
                                </div>

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Number of hours</label>
                                        <input type="text" id="name" x-model="formData.number_of_hours"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                    <div class="w-full">
                                        <label for="name" class="block text-sm text-gray-700">Conducted by</label>
                                        <input type="text" id="name" x-model="formData.conducted_by"
                                            class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Special Skills and Hobbies</label>
                                <input type="text" id="name" x-model="formData.special"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Non-academic Distinctions</label>
                                <input type="text" id="name" x-model="formData.distinctions"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Membership in
                                    associations/organizations</label>
                                <input type="text" id="name" x-model="formData.membership"
                                    class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name" class="block text-sm text-gray-700">Character References</label>
                                <input type="text" id="name" x-model="formData.references"
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black">
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