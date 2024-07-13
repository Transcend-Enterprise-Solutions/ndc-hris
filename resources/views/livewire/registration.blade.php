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
                            Step 1 out of 5: <span class="font-bold text-black">Personal Information</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-2">
                            <div class="w-full">
                                <label for="firstname" class=" text-sm text-gray-700">First Name <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="first_name" wire:model.live="first_name"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="middlename" class=" text-sm text-gray-700">Middle Name</label>
                                <input type="text" id="middle_name" wire:model.live="middle_name"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="surname" class=" text-sm text-gray-700">Surname <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="surname" wire:model.live="surname"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('surname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="suffix" class=" text-sm text-gray-700">Suffix</label>
                                <select id="suffix" wire:model.live="suffix"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="sex" class=" text-sm text-gray-700">Sex at Birth <span
                                        class="text-red-600">*</span></label>
                                <select id="sex" wire:model.live="sex"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('sex') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="w-full">
                                <label for="date_of_birth" class=" text-sm text-gray-700">Birth Date <span
                                        class="text-red-600">*</span></label>
                                <input type="date" id="date_of_birth" wire:model.live="date_of_birth"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="place_of_birth" class=" text-sm text-gray-700">Place of Birth <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="place_of_birth" wire:model.live="place_of_birth"
                                    class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('place_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="blood_type" class=" text-sm text-gray-700">Blood type <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="blood_type" wire:model.live="blood_type"
                                    class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="citizenship" class=" text-sm text-gray-700">Citizenship <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="citizenship" wire:model.live="citizenship"
                                    class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('citizenship') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="civil_status" class=" text-sm text-gray-700">Civil Status <span
                                        class="text-red-600">*</span></label>
                                <select id="civil_status" wire:model.live="civil_status"
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

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="height" class=" text-sm text-gray-700">Height (m) <span
                                        class="text-red-600">*</span></label>
                                <input type="number" id="height" wire:model.live="height"
                                    class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('height') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="weight" class=" text-sm text-gray-700">Weight (kg) <span
                                        class="text-red-600">*</span></label>
                                <input type="number" id="weight" wire:model.live="weight"
                                    class=" w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-12 columns-1">
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="toStep2" wire:loading.attr="disabled" wire:target="toStep2">
                                    <span wire:loading.remove wire:target="toStep2">Next</span>
                                    <span wire:loading wire:target="toStep2">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 2 -->
                    @if ($step === 2)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 2 out of 5: <span class="font-bold text-black">Government IDs</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="text-sm text-gray-700">GSIS ID No. <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="gsis" wire:model.live="gsis"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('gsis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="name" class="text-sm text-gray-700">PAGIBIG ID No. <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="pagibig" wire:model.live="pagibig"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('pagibig') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="text-sm text-gray-700">PhilHealth ID No. <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="philhealth" wire:model.live="philhealth"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('philhealth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="name" class="text-sm text-gray-700">SSS No. <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="sss" wire:model.live="sss"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('sss') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="name" class="text-sm text-gray-700">TIN No. <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="tin" wire:model.live="tin"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('tin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="agency_employee_no" class="text-sm text-gray-700">Agency Employee
                                    No. <span class="text-red-600">*</span></label>
                                <input type="text" id="agency_employee_no" wire:model.live="agency_employee_no"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('agency_employee_no') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep">
                                    <span wire:loading.remove wire:target="prevStep">Previous</span>
                                    <span wire:loading wire:target="prevStep">Loading...</span>
                                </button>
                            </div>
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="toStep3" wire:loading.attr="disabled" wire:target="toStep3">
                                    <span wire:loading.remove wire:target="toStep3">Next</span>
                                    <span wire:loading wire:target="toStep3">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 3 -->
                    @if ($step === 3)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 3 out of 5: <span class="font-bold text-black">Contact Information</span>
                        </h2>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                            <legend class="text-black">Permanent Address</legend>
                            <div class="mt-2">
                                <div class="w-full mt-2">
                                    <label for="permanent_region" class="block text-sm text-gray-700">Region <span
                                            class="text-red-600">*</span></label>
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
                                            class="text-red-600">*</span></label>
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
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="permanent_selectedCity" id="permanent_city"
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
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="permanent_selectedBarangay" id="permanent_barangay"
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
                                        Street | Subdivision <span class="text-red-600">*</span></label>
                                    <input type="text" id="p_house_street" wire:model.live="p_house_street"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        name="p_house_street" required>
                                    @error('p_house_street') <span class="text-red-500 text-sm">The Street and Barangay
                                        field is required</span> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-4 mb-4 gap-2 columns-1">
                            <input type="checkbox" wire:model.live="same_as_above">
                            <label class="text-sm text-gray-700">Same As Above (Residential Address)</label>
                        </div>

                        @if(!$same_as_above)
                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4">
                            <legend class="text-black">Residential Address</legend>
                            <div class="mt-2">
                                <div class="w-full mt-2">
                                    <label for="residential_region" class="block text-sm text-gray-700">Region <span
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="residential_selectedRegion" id="residential_region"
                                        name="residential_selectedRegion" required>
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
                                    @error('residential_selectedRegion') <span class="text-red-500 text-sm">The Region
                                        Field is required</span> @enderror
                                </div>

                                <div class="w-full mt-2">
                                    <label for="residential_province" class="block text-sm text-gray-700">Province <span
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="residential_selectedProvince" id="residential_province"
                                        name="residential_selectedProvince" required>
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
                                    @error('residential_selectedProvince') <span class="text-red-500 text-sm">The
                                        Province
                                        Field is required</span> @enderror
                                </div>

                                <div class="w-full mt-2">
                                    <label for="residential_city" class="block text-sm text-gray-700">City <span
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="residential_selectedCity" id="residential_city"
                                        name="residential_selectedCity" required>
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
                                    @error('residential_selectedCity') <span class="text-red-500 text-sm">The City field
                                        is required</span> @enderror
                                </div>

                                <div class="w-full mt-2">
                                    <label for="residential_barangay" class="block text-sm text-gray-700">Barangay <span
                                            class="text-red-600">*</span></label>
                                    <select
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        wire:model.live="residential_selectedBarangay" id="residential_barangay"
                                        name="residential_selectedBarangay" required>
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
                                    @error('residential_selectedBarangay') <span class="text-red-500 text-sm">The
                                        Barangay
                                        field is required</span> @enderror
                                </div>

                                <div class="w-full mt-2">
                                    <label for="r_house_street" class="block text-sm text-gray-700">House Number |
                                        Street | Subdivision <span class="text-red-600">*</span></label>
                                    <input type="text" id="r_house_street" wire:model.live="r_house_street"
                                        class="block w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-white border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        name="r_house_street" required>
                                    @error('r_house_street') <span class="text-red-500 text-sm">The Street and Barangay
                                        field is required</span> @enderror
                                </div>
                            </div>
                        </fieldset>
                        @endif

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="tel_number" class="text-sm text-gray-700">Telephone No.</label>
                                <input type="text" id="tel_number" wire:model.live="tel_number"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                            <div class="w-full">
                                <label for="mobile_number" class="text-sm text-gray-700">Mobile No. <span
                                        class="text-red-600">*</span></label>
                                <input type="number" id="mobile_number" wire:model.live="mobile_number"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('mobile_number') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="email" class="text-sm text-gray-700">Email Address <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="email" wire:model.live="email"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep">
                                    <span wire:loading.remove wire:target="prevStep">Previous</span>
                                    <span wire:loading wire:target="prevStep">Loading...</span>
                                </button>
                            </div>
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="toStep4" wire:loading.attr="disabled" wire:target="toStep4">
                                    <span wire:loading.remove wire:target="toStep4">Next</span>
                                    <span wire:loading wire:target="toStep4">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 4 -->
                    @if ($step === 4)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 4 out of 5: <span class="font-bold text-black">Family Information</span>
                        </h2>

                        <div class="mt-4 gap-2 columns-1">
                            <input type="checkbox" wire:model.live="have_spouse">
                            <label class="text-sm text-gray-700">Do you have a spouse? </label>
                        </div>

                        @if ($have_spouse)
                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="spouse_name" class=" text-sm text-gray-700">Spouse Name</label>
                                <input type="text" id="spouse_name" wire:model.live="spouse_name"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="spouse_birth_date" class=" text-sm text-gray-700">Spouse Birth Date</label>
                                <input type="date" id="spouse_birth_date" wire:model.live="spouse_birth_date"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Select date">
                            </div>
                            <div class="w-full">
                                <label for="spouse_occupation" class=" text-sm text-gray-700">Spouse Occupation</label>
                                <input type="text" id="spouse_occupation" wire:model.live="spouse_occupation"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="spouse_employer" class=" text-sm text-gray-700">Spouse Employer</label>
                                <input type="text" id="spouse_employer" wire:model.live="spouse_employer"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 gap-2 columns-1">
                            <input type="checkbox" wire:model.live="have_child">
                            <label class="text-sm text-gray-700">Do you have a child? </label>
                        </div>

                        @if ($have_child)
                        <fieldset
                            class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4 mt-4 relative">
                            <legend class="text-black">List of Child</legend>
                            <button type="button" class="absolute top-0 right-0 text-black pr-4" wire:click="addChild">
                                <i class="bi bi-plus-square"></i>
                            </button>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                @foreach ($children as $index => $child)
                                <div>
                                    <label for="childrens_name_{{ $index }}" class="text-sm text-gray-700">Children's
                                        Name</label>
                                    <input type="text" id="childrens_name_{{ $index }}"
                                        wire:model="children.{{ $index }}.name"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                </div>
                                <div>
                                    <label for="childrens_birth_date_{{ $index }}"
                                        class="text-sm text-gray-700">Children's Birth Date</label>
                                    <input type="date" id="childrens_birth_date_{{ $index }}"
                                        wire:model="children.{{ $index }}.birth_date"
                                        class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                        placeholder="Select date">
                                </div>
                                @endforeach
                            </div>
                        </fieldset>
                        @endif



                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="fathers_name" class=" text-sm text-gray-700">Fathers Name <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="fathers_name" wire:model.live="fathers_name"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('fathers_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="mothers_maiden_name" class=" text-sm text-gray-700">Mothers Maiden
                                    Name <span class="text-red-600">*</span></label>
                                <input type="text" id="mothers_maiden_name" wire:model.live="mothers_maiden_name"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('mothers_maiden_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep">
                                    <span wire:loading.remove wire:target="prevStep">Previous</span>
                                    <span wire:loading wire:target="prevStep">Loading...</span>
                                </button>
                            </div>
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="toStep5" wire:loading.attr="disabled" wire:target="toStep5">
                                    <span wire:loading.remove wire:target="toStep5">Next</span>
                                    <span wire:loading wire:target="toStep5">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- Step 5 -->
                    @if ($step === 5)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 5 out of 5: <span class="font-bold text-black">Educational Background</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="educ_background" class=" text-sm text-gray-700">Highest Educational
                                    Attainment <span class="text-red-600">*</span></label>
                                <select id="educ_background" wire:model.live="educ_background"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    <option value="">Select Option</option>
                                    <option value="Primary">Primary</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Vocational">Vocational</option>
                                    <option value="College">College</option>
                                    <option value="Graduate Studies">Graduate Studies</option>
                                </select>
                                @error('educ_background') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="name_of_school" class=" text-sm text-gray-700">Name of School <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="name_of_school" wire:model.live="name_of_school"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('name_of_school') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="degree" class=" text-sm text-gray-700">Degree <span
                                        class="text-red-600">*</span></label>
                                <input type="text" id="degree" wire:model.live="degree"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('degree') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="year_graduated" class=" text-sm text-gray-700">Year Graduated <span
                                        class="text-red-600">*</span></label>
                                <input type="number" id="year_graduated" wire:model.live="year_graduated"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('year_graduated') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="period_start_date" class=" text-sm text-gray-700">Period of Attendance
                                    (Start) <span class="text-red-600">*</span></label>
                                <input type="date" id="period_start_date" wire:model.live="period_start_date"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('period_start_date') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <label for="period_end_date" class=" text-sm text-gray-700">Period of
                                    Attendance (End) <span class="text-red-600">*</span></label>
                                <input type="date" id="period_end_date" wire:model.live="period_end_date"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('period_end_date') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="relative w-full" x-data="{ show: false }">
                                <label for="password" class="text-sm text-gray-700">Password <span
                                        class="text-red-600">*</span></label>
                                <input :type="show ? 'text' : 'password'" id="password" wire:model.live="password"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                <div class="absolute top-1/2 right-0 pr-3 flex items-center text-sm leading-5">
                                    <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'" @click="show = !show"
                                        class="cursor-pointer"></i>
                                </div>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="relative w-full" x-data="{ show: false }">
                                <label for="c_password" class="text-sm text-gray-700">Confirm Password <span
                                        class="text-red-600">*</span></label>
                                <input :type="show ? 'text' : 'password'" id="c_password" wire:model.live="c_password"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                <div class="absolute top-1/2 right-0 pr-3 flex items-center text-sm leading-5">
                                    <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'" @click="show = !show"
                                        class="cursor-pointer"></i>
                                </div>
                                @error('c_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex gap-2 mt-12 columns-2">
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep">
                                    <span wire:loading.remove wire:target="prevStep">Previous</span>
                                    <span wire:loading wire:target="prevStep">Loading...</span>
                                </button>
                            </div>
                            <div class="w-full relative">
                                <button
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black"
                                    wire:click="submit" wire:loading.attr="disabled" wire:target="submit">
                                    <span wire:loading.remove wire:target="submit">Submit</span>
                                    <span wire:loading wire:target="submit">Submitting...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{--
                    <!-- Step 6 -->
                    @if ($step === 6)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 6 out of 8: <span class="font-bold text-black">Eligibility</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="rating" class=" text-sm text-gray-700">Rating</label>
                                <input type="text" id="rating" wire:model.live="rating"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="exam_date" class=" text-sm text-gray-700">Date of Examination</label>
                                <input type="date" id="exam_date" wire:model.live="exam_date"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Select date">
                                @error('exam_date') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full">
                                <label for="exam_loc" class=" text-sm text-gray-700">Place of Examination</label>
                                <input type="text" id="exam_loc" wire:model.live="exam_loc"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Select date">
                                @error('exam_loc') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-1">
                            <div class="w-full">
                                <label for="license" class=" text-sm text-gray-700">License</label>
                                <input type="text" id="license" wire:model.live="license"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Select date">
                                @error('license') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
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
                                    wire:click="toStep7">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 7 -->
                    @if ($step === 7)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 7 out of 8: <span class="font-bold text-black">Work Experience</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-2">
                            <div class="w-full">
                                <label for="inclusive_dates" class=" text-sm text-gray-700">Inclusive Dates</label>
                                <input type="date" id="inclusive_dates" wire:model.live="inclusive_dates"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Select date">
                                @error('inclusive_dates') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full">
                                <label for="position_title" class=" text-sm text-gray-700">Position Title</label>
                                <input type="text" id="position_title" wire:model.live="position_title"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your position">
                                @error('position_title') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="department" class=" text-sm text-gray-700">Department</label>
                                <input type="text" id="department" wire:model.live="department"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your department">
                                @error('department') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full">
                                <label for="monthly_salary" class=" text-sm text-gray-700">Monthly Salary</label>
                                <input type="text" id="monthly_salary" wire:model.live="monthly_salary"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your monthly salary">
                                @error('monthly_salary') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 gap-2 columns-2">
                            <div class="w-full">
                                <label for="monthly_salary" class=" text-sm text-gray-700">Status of
                                    Appointment</label>
                                <input type="text" id="status_appointment" wire:model.live="status_appointment"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                    placeholder="Enter your monthly salary">
                                @error('status_appointment') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full">
                                <label for="service" class="text-sm text-gray-700">Service</label>
                                <select id="service" wire:model.live="service"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                    <option value="">Select one</option>
                                    <option value="Government Service">Government Service</option>
                                    <option value="Not">Not</option>
                                </select>
                                @error('service') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                                    wire:click="toStep8">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 8 -->
                    @if ($step === 8)
                    <div>
                        <h2 class="mb-4 text-lg font-medium text-gray-500">
                            Step 8 out of 8: <span class="font-bold text-black">Other Relevant Information</span>
                        </h2>

                        <div class="mt-12 gap-2 columns-1">
                            <div class="w-full">
                                <label for="voluntary_works" class=" text-sm text-gray-700">Voluntary Works</label>
                                <input type="text" id="voluntary_works" wire:model.live="voluntary_works"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('voluntary_works') <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full mb-4 mt-4">
                            <legend class="text-black">Learning and Development</legend>
                            <div class="mt-2">

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="training_title" class=" text-sm text-gray-700">Title of
                                            Training</label>
                                        <input type="text" id="training_title" wire:model.live="training_title"
                                            class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                        @error('training_title') <span class="text-red-500 text-sm">{{ $message
                                            }}</span>
                                        @enderror
                                    </div>

                                    <div class="w-full">
                                        <label for="lad_inclusive_dates" class=" text-sm text-gray-700">Inclusive
                                            Dates</label>
                                        <input type="date" id="lad_inclusive_dates"
                                            wire:model.live="lad_inclusive_dates"
                                            class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            placeholder="Select date">
                                        @error('lad_inclusive_dates') <span class="text-red-500 text-sm">{{ $message
                                            }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-2 gap-2 columns-2">
                                    <div class="w-full">
                                        <label for="number_of_hours" class=" text-sm text-gray-700">Number of
                                            hours</label>
                                        <input type="text" id="number_of_hours" wire:model.live="number_of_hours"
                                            class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                                            placeholder="Enter number of hours">
                                        @error('number_of_hours') <span class="text-red-500 text-sm">{{ $message
                                            }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-full">
                                        <label for="conducted_by" class=" text-sm text-gray-700">Conducted by</label>
                                        <input type="text" id="conducted_by" wire:model.live="conducted_by"
                                            class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                        @error('conducted_by') <span class="text-red-500 text-sm">{{ $message
                                            }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="special_skills_and_hobbies" class=" text-sm text-gray-700">Special Skills
                                    and Hobbies</label>
                                <input type="text" id="special_skills_and_hobbies"
                                    wire:model.live="special_skills_and_hobbies"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('special_skills_and_hobbies') <span class="text-red-500 text-sm">{{ $message
                                    }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="distinctions" class=" text-sm text-gray-700">Non-academic
                                    Distinctions</label>
                                <input type="text" id="distinctions" wire:model.live="distinctions"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('distinctions') <span class="text-red-500 text-sm">{{ $message
                                    }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="membership" class=" text-sm text-gray-700">Membership in
                                    Associations/Organizations</label>
                                <input type="text" id="membership" wire:model.live="membership"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('membership') <span class="text-red-500 text-sm">{{ $message
                                    }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2 gap-2 columns-1">
                            <div class="w-full">
                                <label for="references" class=" text-sm text-gray-700">Character References</label>
                                <input type="text" id="references" wire:model.live="references"
                                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm">
                                @error('references') <span class="text-red-500 text-sm">{{ $message
                                    }}</span>
                                @enderror
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
                                    class="inline-flex items-center justify-center w-full h-12 gap-3 px-5 py-3 font-medium text-white bg-blue-700 rounded-xl hover:bg-blue-500 focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif --}}

                </div>
            </div>
        </div>
        <!-- Ends component -->
    </div>
</section>