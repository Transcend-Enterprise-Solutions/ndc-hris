<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-4/5 bg-white rounded-lg p-3 shadow">

            <div class="pt-4 pb-4">
                <h1 class="text-lg font-bold text-center">PERSONAL DATA SHEET</h1>
            </div>

            <div class="rounded-lg overflow-hidden pb-3">
                <div class="bg-gray-200 p-2">I. Personal Information</div>
                <div>
                    <div class="flex">
                        <p class="border p-1 w-1/5 bg-gray-50">Surname</p>
                        <p class="w-full border p-1">{{ $userData->surname }}</p>
                    </div>

                    <div class="flex">
                        <p class="border p-1 w-1/5 bg-gray-50">Firstname</p>
                        <p class="w-3/5 border p-1">{{ $userData->first_name }}</p>
                        <p class="border p-1 w-1/5 bg-gray-50">Name Extension</p>
                        <p class="w-1/5 border p-1">{{ $userData->name_extension }}</p>
                    </div>

                    <div class="flex">
                        <p class="border p-1 w-1/5 bg-gray-50">Middlename</p>
                        <p class="w-full border p-1">{{ $userData->middle_name }}</p>
                    </div>

                    <div class="flex w-full">
                        <div class="w-2/4">
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Date of Birth</p>
                                <p class="w-full border p-1">{{ $userData->date_of_birth }}</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Place of Birth</p>
                                <p class="w-full border p-1">{{ $userData->place_of_birth }}</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Sex at Birth</p>
                                <p class="w-full border p-1">{{ $userData->sex }}</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Civil Status</p>
                                <p class="w-full border p-1">{{ $userData->civil_status }}</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Citizenship</p>
                                <p class="w-full border p-1">{{ $userData->citizenship }}</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Height</p>
                                <p class="w-full border p-1">{{ $userData->height }}m</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Weight</p>
                                <p class="w-full border p-1">{{ $userData->weight }}kg</p>
                            </div>
                            <div class="flex">
                                <p class="border p-1 w-3/6 bg-gray-50">Bloodtype</p>
                                <p class="w-full border p-1">{{ $userData->blood_type }}</p>
                            </div>
                        </div>
                        <div class="w-2/4">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
