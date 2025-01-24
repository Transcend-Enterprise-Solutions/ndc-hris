<div class="w-full" x-data="{
    selectedTab: 'C1',
}" x-cloak>

    <style>
        @media (max-width: 1024px) {
            .custom-d {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .m-scrollable {
                width: 100%;
                overflow-x: scroll;
            }
        }

        @media (min-width:1024px) {
            .custom-p {
                padding-bottom: 14px !important;
            }
        }

        @-webkit-keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: white;
        }
    </style>

    {{-- Main Display --}}
    <div class="flex justify-center w-full">
        <div class="overflow-x-auto w-full bg-white rounded-2xl p-3 shadow dark:bg-gray-800">

            <div class="pt-4 pb-4">
                <h1 class="text-lg font-bold text-center text-black dark:text-white">Work Experience Sheet</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                @if($moveResizeSig)
                    <div class="w-full bg-gray-100 dark:bg-slate-900 rounded-lg p-4">
                        <p class="w-full text-center text-gray-800 dark:text-gray-100 mb-2">Move and Resize Signature</p>
                        <div class="w-full flex-col sm:flex-row sm:justify-end sm:space-x-4
                        relative text-left mb-6 sm:mb-0 flex gap-4 items-end">
                            <div class="w-full">
                                <label for="sigXPos" class="text-sm block font-medium">X-Pos: {{ $sigXPos }}</label>
                                <input type="range" id="sigXPos" min="0" max="300" wire:model.live="sigXPos" class="w-full">
                            </div>
                        
                            <div class="w-full">
                                <label for="sigYPos" class="text-sm block font-medium">Y-Pos: {{ $sigYPos }}</label>
                                <input type="range" id="sigYPos" min="-100" max="100" wire:model.live="sigYPos" class="w-full">
                            </div>
                        
                            <div class="w-full">
                                <label for="sigSize" class="text-sm block font-medium">Size: {{ $sigSize }}</label>
                                <input type="range" id="sigSize" min="50" max="200" wire:model.live="sigSize" class="w-full">
                            </div>
                        </div>
                    </div>
                @endif
                

                <!-- Export Work Experience Sheet -->
                <div
                    class="w-full flex flex-col sm:flex-row sm:justify-end sm:space-x-4 relative text-left mb-6 sm:mb-0">
                    <button wire:click="toggleAddWorkExp"
                        class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                        justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                        text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                        rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                        type="button" title="Export Work Experience">
                        Add Work Experience
                    </button>
                    <button wire:click="toggleEditWorkExp"
                        class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                        justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                        text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                        rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                        type="button" title="Edit Work Experience">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button wire:click="toggleMoveResizeSig"
                        class="peer mt-4 sm:mt-1 inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                        justify-center px-4 py-1.5 text-sm font-medium tracking-wide 
                        text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                        rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none {{ $moveResizeSig ? 'bg-gray-100 dark:bg-slate-900' : '' }}"
                        type="button" title="Move and Resize Signature">
                        <img class="flex dark:hidden" src="/images/iconsign_black.png" width="22" alt="">
                        <img class="hidden dark:block" src="/images/iconsign_white.png" width="22" alt="">
                        <span class="ml-2 {{ $moveResizeSig ? '' : 'hidden' }}">Close</span>
                    </button>
                </div>
            </div>
            
           
            <div class="mt-2" style="overflow: hidden;">
                <iframe id="pdfIframe" src="data:application/pdf;base64,{{ $pdfContent }}"
                    style="width: 100%; max-height: 80vh; min-height: 500px;" frameborder="0"></iframe>
            </div>

        </div>
    </div>

    {{-- Work Experience Add and Edit Modal --}}
    <x-modal id="workExpModal" maxWidth="2xl" wire:model="editWorkExp">
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $addWorkExp ? 'Add' : 'Edit' }} Work Experience
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Form fields --}}
            <form wire:submit.prevent='saveWorkExp'>
                <div class="grid grid-cols-1">

                    @if (!$addWorkExp)
                        @foreach ($workExperiences as $index => $exp)
                            <div class="grid grid-cols-2 gap-4 mb-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="comp_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Department/Agency/Office/Company</label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.department"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.department')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="gov_service_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Gov't
                                        Service</label>
                                    <div
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                        <label class="inline-flex items-center">
                                            <input type="radio" id="gov_service_yes_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.gov_service"
                                                value="1" class="form-radio text-green-600">
                                            <span class="ml-2">Yes</span>
                                        </label>
                                        <label class="inline-flex items-center ml-6">
                                            <input type="radio" id="gov_service_no_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.gov_service"
                                                value="0" class="form-radio text-green-600">
                                            <span class="ml-2">No</span>
                                        </label>
                                    </div>
                                    @error('workExperiences.' . $index . '.gov_service')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="workExperiences.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $workExperiences[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $workExperiences[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="workExperiences.{{ $index }}.toPresent"
                                                value="Present" @if ($workExperiences[$index]['toPresent']) checked @endif>
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('workExperiences.' . $index . '.end_date' || 'workExperiences.' . $index .
                                        '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position</label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('workExperiences.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="status_of_appointment_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Status of
                                        Appointment</label>
                                    <input type="text" id="status_of_appointment_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.status_of_appointment"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="monthly_salary_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Monthly
                                        Salary</label>
                                    <input type="number" step="0.01" id="monthly_salary_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.monthly_salary"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="sg_step_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Salary/Job/Pay
                                        Grade & Step (if applicable)</label>
                                    <input type="text" id="sg_step_{{ $index }}"
                                        wire:model="workExperiences.{{ $index }}.sg_step"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                        placeholder="Format (00-0) / Increment">
                                </div>

                                @if($workExperiences[$index]['gov_service'])
                                    <fieldset class="border border-gray-300 rounded-md pt-2 pl-2 pr-2 pb-4 col-span-2 grid grid-cols-2 gap-4">
                                        <legend class="px-2">For Service Record</legend>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="pera_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Longevity Pay/Allowance</label>
                                            <input type="number" step="0.01" id="pera_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.pera"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="branch_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Branch of Service</label>
                                            <select name="branch" id="branch_{{ $index }}" class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700"
                                                wire:model="workExperiences.{{ $index }}.branch">
                                                <option value="">Select Branch of Service</option>
                                                <option value="NGA">NGA</option>
                                                <option value="LGU">LGU</option>
                                                <option value="SUCcs">SUCcs</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="leave_absence_wo_pay_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Leave/Absences W/O Pay</label>
                                            <input type="number" id="leave_absence_wo_pay_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.leave_absence_wo_pay"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label for="remarks_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Remarks</label>
                                            <input type="text" id="remarks_{{ $index }}"
                                                wire:model="workExperiences.{{ $index }}.remarks"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                        </div>
                                    </fieldset>
                                @endif

                            </div>
                        @endforeach
                    @else
                        @foreach ($newWorkExperiences as $index => $exp)
                            <div
                                class="grid grid-cols-2 gap-4 p-2 bg-gray-100 dark:bg-slate-700 rounded-lg pb-5 mb-3">

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="comp_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of the Office/Unit
                                        <i class="fas fa-times flex sm:hidden cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewWorkExp({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="comp_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.office_unit"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.office_unit')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="position_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Position 
                                        <i class="fas fa-times hidden sm:flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                            wire:click="removeNewWorkExp({{ $index }})"></i>
                                    </label>
                                    <input type="text" id="position_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.position"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.position')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="start_date_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start
                                        Date</label>
                                    <input type="date" id="start_date_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.start_date"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.start_date')
                                        <span class="text-red-500 text-sm">The start date is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="to_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">End
                                        Date</label>
                                    <div class="flex gap-4">
                                        <input type="date" id="to_{{ $index }}"
                                            wire:model="newWorkExperiences.{{ $index }}.end_date"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700 {{ $newWorkExperiences[$index]['toPresent'] ? 'hidden' : '' }}">
                                        <div
                                            class="flex items-center justify-center gap-2 mr-4 {{ $newWorkExperiences[$index]['toPresent'] ? 'flex-row mt-4' : 'flex-col' }}">
                                            <input type="checkbox" id="to_{{ $index }}"
                                                wire:model.live="newWorkExperiences.{{ $index }}.toPresent"
                                                value="Present">
                                            <label for="to_{{ $index }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Present</label>
                                        </div>
                                    </div>
                                    @error('newWorkExperiences.' . $index . '.end_date' || 'newWorkExperiences.' .
                                        $index . '.toPresent')
                                        <span class="text-red-500 text-sm">The end period of attendance is
                                            required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="supervisor_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Immediate Supervisor</label>
                                    <input type="text" id="supervisor_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.supervisor"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.supervisor')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="agency_org_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Name of Agency/Organization and Location</label>
                                    <input type="text" id="agency_org_{{ $index }}"
                                        wire:model="newWorkExperiences.{{ $index }}.agency_org"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    @error('newWorkExperiences.' . $index . '.agency_org')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                <div class="col-span-2">
                                    <label for="list_accomp_cont_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">List of Accomplishments and Contributions (if any)</label>
                                    <div class="flex relative">
                                        <input type="text" id="list_accomp_cont_{{ $index }}"
                                            wire:model="accoms_cont"
                                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 {{ (empty($newWorkExperiences[$index]["list_accomp_cont"])) ? 'rounded-md' : 'rounded-t-md' }} dark:text-gray-300 dark:bg-gray-700">
                                        <button wire:click="addAccomplishment({{ $index }})"
                                            class="peer inline-flex items-center
                                            justify-center px-4 py-1.5 text-sm font-medium tracking-wide
                                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 focus:outline-none absolute"
                                            type="button" title="Add Accomplishment/Contribution" style="right: 5px; top: 5px;">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                    @if(!empty($newWorkExperiences[$index]['list_accomp_cont']))
                                        <div class="border-l border-r border-b border-gray-300 rounded-b-md dark:bg-gray-800 overflow-hidden">
                                            @foreach ($newWorkExperiences[$index]['list_accomp_cont'] as $i => $accoms)
                                                <div class="w-full dark:bg-gray-700 p-4 flex items-center">
                                                    <div class="w-full">
                                                        <label for="sum_of_duties_{{ $index }}"
                                                            class="block text-sm font-medium text-gray-800 dark:text-gray-50">{{ $accoms }}</label>
                                                    </div>
                                                    <i class="fas fa-times flex cursor-pointer text-red-500 hover:text-red-700 float-right mr-1"
                                                    wire:click="removeAccomplishment({{ $index }}, {{ $i }})"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>


                                <div class="col-span-2">
                                    <label for="sum_of_duties_{{ $index }}"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Summary of Actual Duties</label>
                                    <textarea type="text" id="sum_of_duties_{{ $index }}" rows="4"
                                        wire:model="newWorkExperiences.{{ $index }}.sum_of_duties"
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  dark:text-gray-300 dark:bg-gray-700">
                                    </textarea>
                                    @error('newWorkExperiences.' . $index . '.sum_of_duties')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach

                        <button type="button" wire:click="addNewWorkExp"
                            class="bg-blue-500 hover:bg-blue-700 hover:text-white text-slate-700 dark:text-gray-300 font-bold py-2 px-4 rounded mb-4">
                            Add Work Experience
                        </button>
                    @endif

                    {{-- Save and Cancel buttons --}}
                    <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <div wire:loading wire:target="saveWorkExp" style="margin-bottom: 5px;">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                            Save
                        </button>
                        <p @click="show = false"
                            class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                            Cancel
                        </p>
                    </div>

                </div>
            </form>

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

    function resizeIframe() {
        const iframe = document.getElementById('pdfIframe');
        const pdfDocument = iframe.contentDocument || iframe.contentWindow.document;

        if (pdfDocument) {
            // Set the iframe height based on the content
            iframe.style.height = pdfDocument.body.scrollHeight + 'px';
        }
    }

    // Adjust iframe size when the PDF is loaded
    document.getElementById('pdfIframe').onload = resizeIframe;

    // Optional: Adjust iframe size when the window is resized
    window.onresize = resizeIframe;
</script>
