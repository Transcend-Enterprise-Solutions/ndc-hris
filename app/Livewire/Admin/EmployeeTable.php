<?php

namespace App\Livewire\Admin;

use App\Exports\PDSExport;
use App\Models\LearningAndDevelopment;
use App\Models\PdsC4Answers;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Models\PdsGovIssuedId;
use App\Models\PhilippineProvinces;
use App\Models\PhilippineCities;
use App\Models\PhilippineBarangays;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeTable extends Component
{
    use WithPagination;

    public $employeesChildren;
    // public $selectedUser;
    public $selectedUserData;
    public $p_full_address;
    public $r_full_address;
    public $childrenNames;
    public $childrenBirthDates;

    public $filters = [
        'name' => true,
        'date_of_birth' => true,
        'place_of_birth' => true,
        'sex' => true,
        'citizenship' => true,
        'civil_status' => false,
        'height' => false,
        'weight' => false,
        'blood_type' => false,
        'gsis' => false,
        'pagibig' => false,
        'philhealth' => false,
        'sss' => false,
        'tin' => false,
        'agency_employee_no' => false,
        'permanent_selectedProvince' => false,
        'permanent_selectedCity' => false,
        'permanent_selectedBarangay' => false,
        'p_house_street' => false,
        'permanent_selectedZipcode' => false,
        'residential_selectedProvince' => false,
        'residential_selectedCity' => false,
        'residential_selectedBarangay' => false,
        'r_house_street' => false,
        'residential_selectedZipcode' => false,
        'active_status' => true,
        'appointment' => true,
        'date_hired' => true,
        'years_in_gov_service' => true,
        'learning_and_development' => true,
        // 'tel_number' => false,
        // 'mobile_number' => false,
        // 'email' => false,
    ];

    public $sex;
    public $civil_status;
    public $selectedCivilStatuses = [];
    // public $selectedProvince;
    // public $selectedCity;
    // public $selectedBarangay;
    public $selectedProvinces = [];
    public $selectedCities = [];
    public $selectedBarangays = [];
    public $selectedLD = [];
    public $provinces;
    public $cities;
    public $barangays;
    public $selectAllProvinces = false;
    public $selectAllCities = false;
    public $selectAllBarangays = false;

    public $selectedUser = null;
    public $dropdownForCategoryOpen = false;
    public $dropdownForFilter = false;
    public $dropdownForSexOpen = false;
    public $dropdownForCivilStatusOpen = false;
    public $dropdownForLDOpen = false;
    public $dropdownForProvinceOpen = false;
    public $dropdownForCityOpen = false;
    public $dropdownForBarangayOpen = false;
    public $personalDataSheetOpen = false;

    // User's Data
    public $userData;
    public $userSpouse;
    public $userMother;
    public $userFather;
    public $userChildren;
    public $educBackground;
    public $eligibility;
    public $workExperience;
    public $voluntaryWorks;
    public $lds;
    public $skills;
    public $hobbies;
    public $non_acads_distinctions;
    public $assOrgMemberships;
    public $references;

    public $pds;

    public $search = '';

    protected $listeners = [
        'exportUsers'
    ];

    public $q34aAnswer;
    public $q34bAnswer;
    public $q34bDetails;
    public $q35aAnswer;
    public $q35aDetails;
    public $q35bAnswer;
    public $q35bDate_filed;
    public $q35bStatus;
    public $q36aAnswer;
    public $q36aDetails;
    public $q37aAnswer;
    public $q37aDetails;
    public $q38aAnswer;
    public $q38aDetails;
    public $q38bAnswer;
    public $q38bDetails;
    public $q39aAnswer;
    public $q39aDetails;
    public $q40aAnswer;
    public $q40aDetails;
    public $q40bAnswer;
    public $q40bDetails;
    public $q40cAnswer;
    public $q40cDetails;

    public $editGovId;
    public $govId;
    public $idNumber;
    public $dateIssued;

    public function toggleDropdown(){
        $this->dropdownForCategoryOpen = !$this->dropdownForCategoryOpen;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
    }

    public function toggleDropdownFilter(){
        $this->dropdownForFilter = !$this->dropdownForFilter;
    }

    public function toggleDropdownSex()
    {
        $this->dropdownForSexOpen = !$this->dropdownForSexOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
        $this->dropdownForLDOpen = false;
    }

    public function toggleDropdownCivilStatus()
    {
        $this->dropdownForCivilStatusOpen = !$this->dropdownForCivilStatusOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
        $this->dropdownForLDOpen = false;
    }

    public function toggleDropdownLD()
    {
        $this->dropdownForLDOpen = !$this->dropdownForLDOpen;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
    }

    public function toggleDropdownProvince()
    {
        $this->dropdownForProvinceOpen = !$this->dropdownForProvinceOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
        $this->dropdownForLDOpen = false;
    }

    public function toggleDropdownCity()
    {
        $this->dropdownForCityOpen = !$this->dropdownForCityOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForBarangayOpen = false;
        $this->dropdownForLDOpen = false;
    }

    public function toggleDropdownBarangay()
    {
        $this->dropdownForBarangayOpen = !$this->dropdownForBarangayOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForLDOpen = false;
    }

    public function updatedSelectAllProvinces($value)
    {
        if ($value) {
            $this->selectedProvinces = $this->provinces->pluck('province_description')->toArray();
        } else {
            $this->selectedProvinces = [];
        }
    }

    public function updatedSelectAllCities($value)
    {
        if ($value) {
            $this->selectedCities = $this->cities->pluck('city_municipality_description')->toArray();
        } else {
            $this->selectedCities = [];
        }
    }

    public function updatedSelectAllBarangays($value)
    {
        if ($value) {
            $this->selectedBarangays = $this->barangays->pluck('barangay_description')->toArray();
        } else {
            $this->selectedBarangays = [];
        }
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->checkFilter();

        $query = User::join('user_data', 'user_data.user_id', '=', 'users.id')
                ->leftJoin('learning_and_development', 'learning_and_development.user_id', 'users.id')
                ->select('users.id')
                ->groupBy('users.id')
                ->when($this->filters['name'], function ($query) {
                    $query->addSelect('users.name');
                    $query->groupBy('users.name');
                })
                ->when($this->filters['date_of_birth'], function ($query) {
                    $query->addSelect('user_data.date_of_birth');
                    $query->groupBy('user_data.date_of_birth');
                })
                ->when($this->filters['place_of_birth'], function ($query) {
                    $query->addSelect('user_data.place_of_birth');
                    $query->groupBy('user_data.place_of_birth');
                })
                ->when($this->filters['sex'], function ($query) {
                    $query->addSelect('user_data.sex');
                    $query->groupBy('user_data.sex');
                })
                ->when($this->filters['civil_status'], function ($query) {
                    $query->addSelect('user_data.civil_status');
                    $query->groupBy('user_data.civil_status');
                })
                ->when($this->filters['citizenship'], function ($query) {
                    $query->addSelect('user_data.citizenship');
                    $query->groupBy('user_data.citizenship');
                })
                ->when($this->filters['height'], function ($query) {
                    $query->addSelect('user_data.height');
                    $query->groupBy('user_data.height');
                })
                ->when($this->filters['weight'], function ($query) {
                    $query->addSelect('user_data.weight');
                    $query->groupBy('user_data.weight');
                })
                ->when($this->filters['blood_type'], function ($query) {
                    $query->addSelect('user_data.blood_type');
                    $query->groupBy('user_data.blood_type');
                })
                ->when($this->filters['gsis'], function ($query) {
                    $query->addSelect('user_data.gsis');
                    $query->groupBy('user_data.gsis');
                })
                ->when($this->filters['pagibig'], function ($query) {
                    $query->addSelect('user_data.pagibig');
                    $query->groupBy('user_data.pagibig');
                })
                ->when($this->filters['philhealth'], function ($query) {
                    $query->addSelect('user_data.philhealth');
                    $query->groupBy('user_data.philhealth');
                })
                ->when($this->filters['sss'], function ($query) {
                    $query->addSelect('user_data.sss');
                    $query->groupBy('user_data.sss');
                })
                ->when($this->filters['tin'], function ($query) {
                    $query->addSelect('user_data.tin');
                    $query->groupBy('user_data.tin');
                })
                ->when($this->filters['agency_employee_no'], function ($query) {
                    $query->addSelect('user_data.agency_employee_no');
                    $query->groupBy('user_data.agency_employee_no');
                })
                ->when($this->filters['permanent_selectedProvince'], function ($query) {
                    $query->addSelect('user_data.permanent_selectedProvince');
                    $query->groupBy('user_data.permanent_selectedProvince');
                })
                ->when($this->filters['permanent_selectedCity'], function ($query) {
                    $query->addSelect('user_data.permanent_selectedCity');
                    $query->groupBy('user_data.permanent_selectedCity');
                })
                ->when($this->filters['permanent_selectedBarangay'], function ($query) {
                    $query->addSelect('user_data.permanent_selectedBarangay');
                    $query->groupBy('user_data.permanent_selectedBarangay');
                })
                ->when($this->filters['p_house_street'], function ($query) {
                    $query->addSelect('user_data.p_house_street');
                    $query->groupBy('user_data.p_house_street');
                })
                ->when($this->filters['permanent_selectedZipcode'], function ($query) {
                    $query->addSelect('user_data.permanent_selectedZipcode');
                    $query->groupBy('user_data.permanent_selectedZipcode');
                })
                ->when($this->filters['residential_selectedProvince'], function ($query) {
                    $query->addSelect('user_data.residential_selectedProvince');
                    $query->groupBy('user_data.residential_selectedProvince');
                })
                ->when($this->filters['residential_selectedCity'], function ($query) {
                    $query->addSelect('user_data.residential_selectedCity');
                    $query->groupBy('user_data.residential_selectedCity');
                })
                ->when($this->filters['residential_selectedBarangay'], function ($query) {
                    $query->addSelect('user_data.residential_selectedBarangay');
                    $query->groupBy('user_data.residential_selectedBarangay');
                })
                ->when($this->filters['r_house_street'], function ($query) {
                    $query->addSelect('user_data.r_house_street');
                    $query->groupBy('user_data.r_house_street');
                })
                ->when($this->filters['residential_selectedZipcode'], function ($query) {
                    $query->addSelect('user_data.residential_selectedZipcode');
                    $query->groupBy('user_data.residential_selectedZipcode');
                })
                ->when($this->filters['active_status'], function ($query) {
                    $query->addSelect('users.active_status');
                    $query->groupBy('users.active_status');
                })
                ->when($this->filters['appointment'], function ($query) {
                    $query->addSelect('user_data.appointment');
                    $query->groupBy('user_data.appointment');
                })
                ->when($this->filters['date_hired'], function ($query) {
                    $query->addSelect('user_data.date_hired');
                    $query->groupBy('user_data.date_hired');
                })
                ->when($this->search, function ($query) {
                    $query->where('users.name', 'like', '%' . $this->search . '%');
                })
                ->when($this->sex, function ($query) {
                    if($this->sex == 'others'){
                        $query->where('user_data.sex', '!=', 'Female')
                            ->where('user_data.sex', '!=', 'Male');
                    }else{
                        $query->where('user_data.sex', $this->sex);
                    }
                })
                ->when($this->civil_status, function ($query) {
                    $query->where('user_data.civil_status', $this->civil_status);
                })
                ->when(!empty($this->selectedCivilStatuses), function ($query) {
                    $query->whereIn('user_data.civil_status', $this->selectedCivilStatuses);
                })
                ->when(!empty($this->selectedProvinces), function ($query) {
                    $query->whereIn('user_data.permanent_selectedProvince', $this->selectedProvinces);
                })
                ->when(!empty($this->selectedCities), function ($query) {
                    $query->whereIn('user_data.permanent_selectedCity', $this->selectedCities);
                })
                ->when(!empty($this->selectedBarangays), function ($query) {
                    $query->whereIn('user_data.permanent_selectedBarangay', $this->selectedBarangays);
                })
                ->when(!empty($this->selectedLD), function ($query) {
                    $query->whereIn('learning_and_development.type_of_ld', $this->selectedLD);
                })
                ->when($this->filters['years_in_gov_service'], function ($query) {
                    $query->addSelect(DB::raw('(
                        SELECT FLOOR(SUM(
                            CASE
                                WHEN work_experience.toPresent = "Present" THEN TIMESTAMPDIFF(MONTH, work_experience.start_date, CURDATE())
                                WHEN work_experience.end_date IS NOT NULL THEN TIMESTAMPDIFF(MONTH, work_experience.start_date, work_experience.end_date)
                                ELSE 0
                            END
                        ) / 12)
                        FROM work_experience
                        WHERE work_experience.user_id = users.id AND work_experience.gov_service = 1
                    ) as years_in_gov_service'));
                })
                ->addSelect('learning_and_development.user_id')
                ->groupBy('learning_and_development.user_id')
                ->where('users.user_role', '=','emp')
                ->paginate(10);

            $query->getCollection()->transform(function ($user) {
                $statusMapping = [
                    0 => 'Inactive',
                    1 => 'Active',
                    2 => 'Retired',
                    3 => 'Resigned'
                ];
                $user->active_status_label = $statusMapping[$user->active_status] ?? 'Unknown';
                
                return $user;
            });

            if($this->dropdownForCategoryOpen){
                $this->dropdownForFilter = null;
            }

            if($this->dropdownForFilter){
                $this->dropdownForCategoryOpen = null;
            }

            $learnDev = LearningAndDevelopment::select('type_of_ld');

            return view('livewire.admin.employee-table', [
                'users' => $query,
                'cities' => $this->cities,
                'barangays' => $this->barangays,
                'learnDev' => $learnDev,
            ]);
    }

    public function showUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->userData = $this->selectedUser->userData;
        $this->userSpouse = $this->selectedUser->employeesSpouse;
        $this->userMother = $this->selectedUser->employeesMother;
        $this->userFather = $this->selectedUser->employeesFather;
        $this->userChildren = $this->selectedUser->employeesChildren;
        $this->educBackground = $this->selectedUser->employeesEducation;
        $this->eligibility = $this->selectedUser->eligibility;
        $this->workExperience = $this->selectedUser->workExperience;
        $this->voluntaryWorks = $this->selectedUser->voluntaryWorks;
        $this->lds = $this->selectedUser->learningAndDevelopment;
        $this->skills = $this->selectedUser->skills;
        $this->hobbies = $this->selectedUser->hobbies;
        $this->non_acads_distinctions = $this->selectedUser->nonAcadDistinctions;
        $this->assOrgMemberships = $this->selectedUser->assOrgMembership;
        $this->references = $this->selectedUser->charReferences;
        $this->getC4Answers();
        $pdsGovId = PdsGovIssuedId::where('user_id', $this->selectedUser->id)->first();
        if($pdsGovId){
            $this->govId = $pdsGovId->gov_id;
            $this->idNumber = $pdsGovId->id_number;
            $this->dateIssued = $pdsGovId->date_of_issuance;
        }

        $this->personalDataSheetOpen = true;
    }

    public function closeUserProfile()
    {
        $this->selectedUser = null;
        $this->selectedUserData = null;
        $this->p_full_address = null;
        $this->r_full_address = null;
        $this->employeesChildren = null;
        $this->childrenNames = null;
        $this->childrenBirthDates = null;
    }

    public function closePersonalDataSheet()
    {
        $this->selectedUser = null;
        $this->personalDataSheetOpen = false;
    }

    // public function exportUsers(){
    //     $filters = [
    //         'sex' => $this->sex,
    //         'civil_status' => $this->civil_status,
    //         'selectedProvince' => $this->selectedProvince,
    //         'selectedCity' => $this->selectedCity,
    //         'selectedBarangay' => $this->selectedBarangay,
    //     ];
    //     return Excel::download(new EmployeesExport($filters), 'EmployeesList.xlsx');
    // }

    // public function exportUsers(){
    //     $filterConditions = [
    //         'sex' => $this->sex,
    //         'civil_status' => $this->civil_status,
    //         'selectedProvince' => $this->selectedProvince,
    //         'selectedCity' => $this->selectedCity,
    //         'selectedBarangay' => $this->selectedBarangay,
    //     ];
    
    //     $selectedColumns = array_keys(array_filter($this->filters));
        
    //     // Include 'name' if any name-related fields are selected
    //     $nameFields = ['surname', 'first_name', 'middle_name', 'name_extension'];
    //     if (count(array_intersect($nameFields, $selectedColumns)) > 0) {
    //         $selectedColumns[] = 'name';
    //     }
    
    //     $selectedColumns = array_unique($selectedColumns);
    
    //     return Excel::download(new EmployeesExport($filterConditions, $selectedColumns), 'EmployeesList.xlsx');
    // }


    public function exportUsers()
    {
        $filterConditions = [
            'sex' => $this->sex ?? null,
            'civil_status' => $this->selectedCivilStatuses ?? [],
            'selectedProvince' => $this->selectedProvinces ?? [],
            'selectedCity' => $this->selectedCities ?? [],
            'selectedBarangay' => $this->selectedBarangays ?? [],
            'selectedLD' => $this->selectedLD ?? [],
        ];
    
        $selectedColumns = array_keys(array_filter($this->filters));
        
        // Include 'name' if any name-related fields are selected
        $nameFields = ['surname', 'first_name', 'middle_name', 'name_extension'];
        if (count(array_intersect($nameFields, $selectedColumns)) > 0) {
            $selectedColumns[] = 'name';
        }

        $fieldsToFormat = ['gsis', 'pagibig', 'philhealth', 'sss', 'tin', 'agency_employee_no'];

        foreach ($fieldsToFormat as $field) {
            if (isset($user->$field) && is_numeric($user->$field)) {
                // Prepend a single quote to make Excel treat the value as text
                $user->$field = "'" . $user->$field;
            }
        }
    
        $selectedColumns = array_unique($selectedColumns);
    
        return Excel::download(new EmployeesExport($filterConditions, $selectedColumns), 'EmployeesList.xlsx');
    }
    
    public function checkFilter()
    {
        if (!empty($this->selectedProvinces)) {
            $provinceCodes = PhilippineProvinces::whereIn('province_description', $this->selectedProvinces)
                ->pluck('province_code');
            $this->cities = PhilippineCities::whereIn('province_code', $provinceCodes)->get();
        } else {
            $this->cities = collect();
            $this->barangays = collect();
        }
    
        if (!empty($this->selectedCities)) {
            $cityCodes = PhilippineCities::whereIn('city_municipality_description', $this->selectedCities)
                ->pluck('city_municipality_code');
            $this->barangays = PhilippineBarangays::whereIn('city_municipality_code', $cityCodes)->get();
        } else {
            $this->barangays = collect();
        }
    }
      

    public function mount(){
        $this->getProvicesAndCities();
    }

    public function getProvicesAndCities(){
        $this->provinces = PhilippineProvinces::all();
        $this->cities = collect();
        $this->barangays = collect();
    }

    public function exportPDS(){
        try {
            $user = User::find($this->selectedUser->id);
            $pds = [
                'userData' => $user->userData,
                'userSpouse' => $user->employeesSpouse,
                'userMother' => $user->employeesMother,
                'userFather' => $user->employeesFather,
                'userChildren' => $user->employeesChildren,
                'educBackground' => $user->employeesEducation,
                'eligibility' => $user->eligibility,
                'workExperience' => $user->workExperience,
                'voluntaryWorks' => $user->voluntaryWorks,
                'lds' => $user->learningAndDevelopment,
                'skills' => $user->skills,
                'hobbies' => $user->hobbies,
                'non_acads_distinctions' => $user->nonAcadDistinctions,
                'assOrgMemberships' => $user->assOrgMembership,
                'references' => $user->charReferences,
                'pds_c4_answers' => $user->pdsC4Answers,
                'pds_gov_id' => $user->pdsGovIssuedId,
                'pds_photo' => $user->pdsPhoto,
            ];

            $exporter = new PDSExport($pds);
            $result = $exporter->export();

            return response()->streamDownload(function () use ($result) {
                echo $result['content'];
            }, $result['filename']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getC4Answers(){
        try {
            $questions = [
                'q34a' => ['num' => 34, 'letter' => 'a', 'fields' => ['answer']],
                'q34b' => ['num' => 34, 'letter' => 'b', 'fields' => ['answer', 'details']],
                'q35a' => ['num' => 35, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q35b' => ['num' => 35, 'letter' => 'b', 'fields' => ['answer', 'date_filed', 'status']],
                'q36a' => ['num' => 36, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q37a' => ['num' => 37, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q38a' => ['num' => 38, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q38b' => ['num' => 38, 'letter' => 'b', 'fields' => ['answer', 'details']],
                'q39a' => ['num' => 39, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q40a' => ['num' => 40, 'letter' => 'a', 'fields' => ['answer', 'details']],
                'q40b' => ['num' => 40, 'letter' => 'b', 'fields' => ['answer', 'details']],
                'q40c' => ['num' => 40, 'letter' => 'c', 'fields' => ['answer', 'details']],
            ];
    
            foreach ($questions as $key => $question) {
                $answer = $this->getAnswer($question['num'], $question['letter']);
                
                foreach ($question['fields'] as $field) {
                    $fieldKey = $key . ucfirst($field);
                    if($answer && $field == "date_filed"){
                        $this->{$fieldKey} = $answer ? Carbon::parse($answer->{$field})->format('m-d-Y') : null;
                    }else{
                        $this->{$fieldKey} = $answer ? $answer->{$field} : null;
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAnswer($qNum, $qLetter){
        return PdsC4Answers::where('user_id', $this->selectedUser->id)
            ->where('question_number', $qNum)
            ->where('question_letter', $qLetter)
            ->first();
    }    

    public function downloadCertificate($documentId)
    {
        $document = LearningAndDevelopment::findOrFail($documentId);
        $filePath = $document->certificate;
        $fileName = basename($filePath);

        if (!Storage::disk('public')->exists($filePath)) {
            throw new NotFoundHttpException("The file does not exist.");
        }

        $fileSize = Storage::disk('public')->size($filePath);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => $fileSize,
        ];

        return new StreamedResponse(function () use ($filePath) {
            $stream = Storage::disk('public')->readStream($filePath);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $headers);
    }
}
