<div x-data="{ open: false }" class="w-full">
    <button wire:click="openLeaveForm"
        class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mx-2 mb-2">
        Apply for Leave
    </button>

    {{-- <button wire:click="openLeaveForm"
        class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mx-2 mb-2">
        See Status of Application
    </button> --}}

    {{-- Leave Application Table --}}
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Application</h1>
                </div>
                <div class="flex flex-col p-3">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block w-full py-2 align-middle">
                            <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Date of Filing</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Type of Leave</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Details of Leave</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Requested Day/s</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Requested Date/s</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Approved Day/s</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Approved Date/s</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Status</th>
                                                <th
                                                    class="px-5 py-3 text-gray-100 text-sm font-medium text-center sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Export</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leaveApplications as $leaveApplication)
                                                <tr class="whitespace-nowrap">
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->date_of_filing }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->type_of_leave }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->details_of_leave }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->number_of_days }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->list_of_dates }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->approved_days ?? 'N/A' }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->approved_dates ?? 'N/A' }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span
                                                            class="inline-block px-3 py-1 text-sm font-semibold 
                                                    {{ str_starts_with($leaveApplication->status, 'Approved')
                                                        ? 'text-green-800 bg-green-200'
                                                        : (str_starts_with($leaveApplication->status, 'Disapproved')
                                                            ? 'text-red-800 bg-red-200'
                                                            : 'text-yellow-800 bg-yellow-200') }} rounded-full">
                                                            {{ $leaveApplication->status }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                        <button type="button"
                                                            wire:click.prevent="exportPDF({{ $leaveApplication->id }})">
                                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-5 border-t border-neutral-500 dark:border-neutral-200">
                                    {{ $leaveApplications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leave Application Form --}}
    <x-modal id="applyForLeave" maxWidth="5xl" wire:model="applyForLeave">
        <div class="p-4">
            <div class="bg-slate-800 rounded-t-lg dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Basic Information
            </div>

            <div class="border p-4">
                <form>
                    <div class="gap-4">
                        <div class="gap-2 columns-1 w-full">
                            <label for="surname"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Fullname</label>
                            <input type="text" id="surname" wire:model='name' disabled
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                        </div>

                        <div class="gap-2 lg:columns-2 sm:columns-1 mt-2">
                            <label for="office_or_department"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Office/Division</label>
                            <input type="text" id="office_or_department" wire:model="office_or_department" disabled
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('office_or_department')
                                <span class="text-red-500 text-sm">This field is
                                    required!</span>
                            @enderror

                            <label for="date_of_filing"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Date of
                                Filing</label>
                            <input type="date" id="date_of_filing" wire:model="date_of_filing" disabled
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                        </div>

                        <div class="gap-2 lg:columns-2 sm:columns-1 mt-2">
                            <label for="position"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Position</label>
                            <input type="text" id="position" wire:model="position" disabled
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('position')
                                <span class="text-red-500 text-sm">This field is required!</span>
                            @enderror

                            <label for="salary"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Salary</label>
                            <input type="number" id="salary" wire:model="salary" disabled
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('salary')
                                <span class="text-red-500 text-sm">This field is required!</span>
                            @enderror
                        </div>

                    </div>
                </form>
            </div>

            {{-- Form fields --}}
            <div class="bg-gray-800 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                Details of Application
            </div>

            <div class="border p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- A. --}}
                <fieldset class="border border-gray-300 p-4 rounded-lg overflow-y-auto w-full h-96 mb-4 md:mb-0">
                    <legend class="text-gray-700 dark:text-slate-100">A. Type of Leave to be availed of</legend>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Vacation Leave" wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Vacation Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Mandatory/Forced Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Mandatory/Forced Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Sick Leave" wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Sick Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Maternity Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Maternity Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Paternity Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Paternity Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Special Privilege Leave" wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Special Privilege Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Solo Parent Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Solo Parent Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Study Leave" wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Study Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="10-Day VAWC Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">10-Day VAWC Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Rehabilitation Privilege" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Rehabilitation Privilege</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Special Leave Benefits for Women"
                            wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Special Leave Benefits for
                            Women</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Special Emergency (Calamity) Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Special Emergency (Calamity)
                            Leave</label>
                    </div>
                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Adoption Leave" wire:model="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Adoption Leave</label>
                    </div>

                    <div class="gap-2 columns-1">
                        <input type="checkbox" value="Others" wire:model.live="type_of_leave">
                        <label class="text-md text-gray-700 dark:text-slate-100">Others (Please specify):</label>

                        @if (in_array('Others', $type_of_leave))
                            <input type="text" id="other_leave" wire:model="other_leave"
                                class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                        @endif
                    </div>

                    @error('type_of_leave')
                        <span class="text-red-500 text-sm">Please choose one!</span>
                    @enderror
                </fieldset>

                {{-- B. --}}
                <fieldset class="border border-gray-300 p-4 rounded-lg w-full h-96 mb-4 md:mb-0 overflow-y-auto">
                    <legend class="text-gray-700 dark:text-slate-100">B. Details of Leave</legend>
                    <div
                        class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto">
                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                            Other purpose:
                        </h6>
                        <div class="gap-2 columns-1">
                            <input type="checkbox" class="ml-1" value="Monetization of Leave Credits"
                                wire:model="details_of_leave">
                            <label class="text-md text-gray-700 dark:text-slate-100">Monetization of Leave
                                Credits</label>
                        </div>
                        <div class="gap-2 columns-1 mt-4">
                            <input type="checkbox" class="ml-1" value="Terminal Leave"
                                wire:model="details_of_leave">
                            <label class="text-md text-gray-700 dark:text-slate-100">Terminal Leave</label>
                        </div>
                    </div>

                    {{-- For Vacation Leave or Special Privilege Leave --}}
                    @if (in_array('Vacation Leave', $type_of_leave) || in_array('Special Privilege Leave', $type_of_leave))
                        <div
                            class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                In
                                case of Vacation/Special Privilege Leave:</h6>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="gap-2 columns-1">
                                    <input type="checkbox" class="ml-1" value="Within the Philippines"
                                        wire:model.live="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">Within the
                                        Philippines</label>
                                    @if (in_array('Within the Philippines', $details_of_leave))
                                        <input type="text" id="within_the_ph" wire:model="philippines"
                                            class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                    @endif
                                </div>
                                <div class="gap-2 columns-1">
                                    <input type="checkbox" class="ml-1" value="Abroad"
                                        wire:model.live="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">Abroad (Specify)</label>
                                    @if (in_array('Abroad', $details_of_leave))
                                        <input type="text" id="abroad_value" wire:model="abroad"
                                            class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- For Sick Leave --}}
                    @if (in_array('Sick Leave', $type_of_leave))
                        <div
                            class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                In
                                case of Sick Leave:</h6>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="gap-2 columns-1">
                                    <input type="checkbox" class="ml-1" value="In Hospital"
                                        wire:model.live="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">In Hospital (Special
                                        Illness)</label>
                                    @if (in_array('In Hospital', $details_of_leave))
                                        <input type="text" id="in_hospital" wire:model="inHospital"
                                            class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                    @endif
                                </div>
                                <div class="gap-2 columns-1">
                                    <input type="checkbox" class="ml-1" value="Out Patient"
                                        wire:model.live="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">Out Patient (Special
                                        Illness)</label>
                                    @if (in_array('Out Patient', $details_of_leave))
                                        <input type="text" id="out_patient" wire:model="outPatient"
                                            class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- For Special Leave Benefits for Women --}}
                    @if (in_array('Special Leave Benefits for Women', $type_of_leave))
                        <div
                            class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                In
                                case of Special Leave Benefits for Women:</h6>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" class="ml-1" value="Women Special Illness"
                                    wire:model.live="details_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">(Special Illness)</label>
                                @if (in_array('Women Special Illness', $details_of_leave))
                                    <input type="text" id="women_leave" wire:model="specialIllnessForWomen"
                                        class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- For Study Leave --}}
                    @if (in_array('Study Leave', $type_of_leave))
                        <div
                            class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                            <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                In
                                case of Study Leave:</h6>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" class="ml-1" value="Completion of Masters Degree"
                                    wire:model="details_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Completion of Master's
                                    Degree</label>
                            </div>
                            <div class="gap-2 columns-1 mt-4">
                                <input type="checkbox" class="ml-1" value="BAR/Board Examination Review"
                                    wire:model="details_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">BAR/Board Examination
                                    Review</label>
                            </div>
                        </div>
                    @endif

                    @error('details_of_leave')
                        <span class="text-red-500 text-sm">Please choose one!</span>
                    @enderror
                </fieldset>

                {{-- C. --}}
                <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                    <legend class="text-gray-700 dark:text-slate-100">C. Number of Working Days Applied for</legend>
                    <div class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700">
                        <div class="gap-2 columns-1">
                            <label class="text-sm text-gray-700 dark:text-slate-100">Days</label>
                            <input type="number" id="number_of_days" wire:model="number_of_days"
                                class="mt-1 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                            @error('number_of_days')
                                <span class="text-red-500 text-sm">This field is
                                    required!</span>
                            @enderror
                        </div>
                        <div class="gap-2 columns-1 mt-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-100">List of
                                Dates</label>
                            <div class="gap-2 columns-2">
                                <input type="date" wire:model="new_date"
                                    class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                <button><i
                                        class="bi bi-plus-square dark:text-slate-50 text-slate-900 hover:text-slate-400"
                                        wire:click="addDate" style="font-size: 2rem;"></i></button>
                            </div>
                        </div>

                        <div class="gap-2 columns-1 mt-2">
                            <ul>
                                @foreach ($list_of_dates as $date)
                                    <li class="dark:text-slate-50 text-slate-900"><i
                                            class="bi bi-check-lg pr-4 text-green-600"></i>{{ $date }}</li>
                                @endforeach
                            </ul>

                            @error('new_date')
                                <span class="text-red-500 text-sm">Please set a valid date!</span>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- D. --}}
                <fieldset class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                    <legend class="text-gray-700 dark:text-slate-100">D. Commutation</legend>
                    <div class="gap-2 columns-1">
                        <input type="radio" value="Requested" wire:model.live="commutation">
                        <label class="text-md text-gray-700 dark:text-slate-100">Requested</label>
                    </div>
                    <div class="gap-2 columns-1 mt-4">
                        <input type="radio" value="Not Requested" wire:model.live="commutation">
                        <label class="text-md text-gray-700 dark:text-slate-100">Not Requested</label>
                    </div>
                    @error('commutation')
                        <span class="text-red-500 text-sm">Please choose one!</span>
                    @enderror
                </fieldset>

                <!-- File upload section -->
                <div class="flex flex-col items-center justify-center w-full col-span-1 md:col-span-2 mt-4 md:mt-0">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click
                                    to upload</span></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF</p>
                        </div>
                        <input id="dropzone-file" type="file" wire:model="files" multiple class="hidden" />
                    </label>

                    <!-- Display selected files -->
                    @if ($files)
                        <div class="mt-4">
                            <ul class="list-disc list-inside">
                                @foreach ($files as $index => $file)
                                    <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        {{ $file->getClientOriginalName() }}
                                        <button type="button" wire:click="removeFile({{ $index }})"
                                            class="ml-2 text-red-500">
                                            &times;
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @error('files.*')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="bg-gray-800 dark:bg-gray-200 p-2 text-white flex justify-center rounded-b-lg border">
                <button wire:click="submitLeaveApplication" role="status"
                    class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mx-2">
                    Submit
                </button>
                <button wire:click="closeLeaveForm" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded mx-2">
                    Close
                </button>
            </div>
        </div>
    </x-modal>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('leaveType', () => ({
            vacationLeave: false,
            specialPrivilegeLeave: false,
            sickLeave: false,
            specialLeaveBenefitsForWomen: false,
            studyLeave: false,
        }));
    });
</script>
