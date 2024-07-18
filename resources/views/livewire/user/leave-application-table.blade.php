<div class="w-full">
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full sm:w-4/5 bg-white rounded-2xl p-5 shadow dark:bg-gray-800">
            <div class="bg-slate-800 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold rounded-t-lg">
                Basic Information
            </div>
            <div class="border p-4">
                <form>
                    <div class="grid grid-cols-2 gap-4 mt-1">
                        <div class="col-span-2 sm:col-span-1">
                            <label for="surname"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Surname</label>
                            <input type="text" id="surname" wire:model='surname'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('surname')
                            <span class="text-red-500 text-sm">The surname is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="first_name"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Firstname</label>
                            <input type="text" id="first_name" wire:model='first_name'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('first_name')
                            <span class="text-red-500 text-sm">The firstname is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="middle_name"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Middlename</label>
                            <input type="text" id="middle_name" wire:model='middle_name'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('middle_name')
                            <span class="text-red-500 text-sm">The middlename is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="name_extension"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Office/Department</label>
                            <input type="text" id="name_extension" wire:model='name_extension'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('name_extension')
                            <span class="text-red-500 text-sm">The name extension is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="date_of_birth"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Date of
                                Filing</label>
                            <input type="date" id="date_of_birth" wire:model='date_of_birth'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('date_of_birth')
                            <span class="text-red-500 text-sm">The date of birth is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="place_of_birth"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Position</label>
                            <input type="text" id="place_of_birth" wire:model='place_of_birth'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('place_of_birth')
                            <span class="text-red-500 text-sm">The place of birth is required!</span>
                            @enderror
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label for="sex"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Salary</label>
                            <input type="text" id="sex" wire:model='sex'
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('sex')
                            <span class="text-red-500 text-sm">The sex is required!</span>
                            @enderror
                        </div>

                    </div>
                </form>
            </div>

            {{-- Form fields --}}
            <div class="bg-gray-400 dark:bg-slate-300 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Details of Application
            </div>
            <div class="border p-4">
                <form>

                </form>
            </div>
        </div>
    </div>
</div>
</div>