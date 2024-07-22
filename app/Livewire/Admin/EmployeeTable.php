<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\UserData;
use Livewire\WithPagination;
use App\Models\EmployeesChildren;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Models\PhilippineProvinces;
use App\Models\PhilippineCities;
use App\Models\PhilippineBarangays;
use Barryvdh\DomPDF\Facade\Pdf;

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
        'civil_status' => true,
        'height' => false,
        'weight' => false,
        'blood_type' => false,
        'gsis' => false,
        'pagibig' => false,
        'philhealth' => false,
        'sss' => false,
        'tin' => false,
        'agency_employee_no' => false,
        'permanent_selectedZipcode' => false,
        'permanent_selectedProvince' => false,
        'permanent_selectedCity' => false,
        'permanent_selectedBarangay' => false,
        'p_house_street' => false,
        'residential_selectedZipcode' => false,
        'residential_selectedProvince' => false,
        'residential_selectedCity' => false,
        'residential_selectedBarangay' => false,
        'r_house_street' => false,
        // 'tel_number' => false,
        // 'mobile_number' => false,
        // 'email' => false,
    ];

    public $sex;
    public $civil_status;
    public $selectedProvince;
    public $selectedCity;
    public $selectedBarangay;
    public $provinces;
    public $cities;
    public $barangays;

    public $selectedUser = null;
    public $dropdownForCategoryOpen = false;
    public $dropdownForFilter = false;
    public $dropdownForSexOpen = false;
    public $dropdownForCivilStatusOpen = false;
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

    protected $listeners = [
        'exportUsers'
    ];

    public function toggleDropdown()
    {
        $this->dropdownForCategoryOpen = !$this->dropdownForCategoryOpen;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForBarangayOpen = false;
    }

    public function toggleDropdownFilter()
    {
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
    }

    public function toggleDropdownCivilStatus()
    {
        $this->dropdownForCivilStatusOpen = !$this->dropdownForCivilStatusOpen;
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
    }

    public function toggleDropdownCity()
    {
        $this->dropdownForCityOpen = !$this->dropdownForCityOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForProvinceOpen = false;
        $this->dropdownForBarangayOpen = false;
    }

    public function toggleDropdownBarangay()
    {
        $this->dropdownForBarangayOpen = !$this->dropdownForBarangayOpen;
        $this->dropdownForCategoryOpen = false;
        $this->dropdownForSexOpen = false;
        $this->dropdownForCivilStatusOpen = false;
        $this->dropdownForCityOpen = false;
        $this->dropdownForProvinceOpen = false;
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->checkFilter();

        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.id')
            ->when($this->filters['name'], function ($query) {
                $query->addSelect('users.name');
            })
            ->when($this->filters['date_of_birth'], function ($query) {
                $query->addSelect('user_data.date_of_birth');
            })
            ->when($this->filters['place_of_birth'], function ($query) {
            $query->addSelect('user_data.place_of_birth');
            })
            ->when($this->filters['sex'], function ($query) {
            $query->addSelect('user_data.sex');
            })
            ->when($this->filters['civil_status'], function ($query) {
            $query->addSelect('user_data.civil_status');
            })
            ->when($this->filters['citizenship'], function ($query) {
            $query->addSelect('user_data.citizenship');
            })
            ->when($this->filters['height'], function ($query) {
            $query->addSelect('user_data.height');
            })
            ->when($this->filters['weight'], function ($query) {
            $query->addSelect('user_data.weight');
            })
            ->when($this->filters['blood_type'], function ($query) {
            $query->addSelect('user_data.blood_type');
            })
            ->when($this->filters['gsis'], function ($query) {
            $query->addSelect('user_data.gsis');
            })
            ->when($this->filters['pagibig'], function ($query) {
            $query->addSelect('user_data.pagibig');
            })
            ->when($this->filters['philhealth'], function ($query) {
            $query->addSelect('user_data.philhealth');
            })
            ->when($this->filters['sss'], function ($query) {
            $query->addSelect('user_data.sss');
            })
            ->when($this->filters['tin'], function ($query) {
            $query->addSelect('user_data.tin');
            })
            ->when($this->filters['agency_employee_no'], function ($query) {
            $query->addSelect('user_data.agency_employee_no');
            })
            ->when($this->filters['permanent_selectedProvince'], function ($query) {
            $query->addSelect('user_data.permanent_selectedProvince');
            })
            ->when($this->filters['permanent_selectedCity'], function ($query) {
            $query->addSelect('user_data.permanent_selectedCity');
            })
            ->when($this->filters['permanent_selectedBarangay'], function ($query) {
            $query->addSelect('user_data.permanent_selectedBarangay');
            })
            ->when($this->filters['p_house_street'], function ($query) {
            $query->addSelect('user_data.p_house_street');
            })
            ->when($this->filters['permanent_selectedZipcode'], function ($query) {
            $query->addSelect('user_data.permanent_selectedZipcode');
            })
            ->when($this->filters['residential_selectedProvince'], function ($query) {
            $query->addSelect('user_data.residential_selectedProvince');
            })
            ->when($this->filters['residential_selectedCity'], function ($query) {
            $query->addSelect('user_data.residential_selectedCity');
            })
            ->when($this->filters['residential_selectedBarangay'], function ($query) {
            $query->addSelect('user_data.residential_selectedBarangay');
            })
            ->when($this->filters['r_house_street'], function ($query) {
            $query->addSelect('user_data.r_house_street');
            })
            ->when($this->filters['residential_selectedZipcode'], function ($query) {
            $query->addSelect('user_data.residential_selectedZipcode');
            })
            ->where(function ($query) {
                if ($this->sex) {
                    $query->where('user_data.sex', $this->sex);
                }
            })
            ->where(function ($query) {
                if ($this->civil_status) {
                    $query->where('user_data.civil_status', $this->civil_status);
                }
            })
            ->when($this->selectedProvince, function ($query) {
                return $query->where('user_data.permanent_selectedProvince', $this->selectedProvince);
            })
            ->when($this->selectedCity, function ($query) {
                return $query->where('user_data.permanent_selectedCity', $this->selectedCity);
            })
            ->when($this->selectedBarangay, function ($query) {
                return $query->where('user_data.permanent_selectedBarangay', $this->selectedBarangay);
            })
            ->paginate(10);

            if($this->dropdownForCategoryOpen){
                $this->dropdownForFilter = null;
            }

            if($this->dropdownForFilter){
                $this->dropdownForCategoryOpen = null;
            }

            return view('livewire.admin.employee-table', [
                'users' => $query,
                'cities' => $this->cities,
                'barangays' => $this->barangays,
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

    public function exportUsers(){
        $filters = [
            'sex' => $this->sex,
            'civil_status' => $this->civil_status,
            'selectedProvince' => $this->selectedProvince,
            'selectedCity' => $this->selectedCity,
            'selectedBarangay' => $this->selectedBarangay,
        ];
        return Excel::download(new EmployeesExport($filters), 'EmployeesList.xlsx');
    }

    public function checkFilter(){
        if ($this->selectedProvince != null) {
            $provinceCode = PhilippineProvinces::where('province_description', $this->selectedProvince)
                            ->select('province_code')->first();
            $provinceCode = $provinceCode->getAttributes();
            $this->cities = PhilippineCities::where('province_code', $provinceCode['province_code'])->get();
        }
        if ($this->selectedCity != null) {
            $cityCode = PhilippineCities::where('city_municipality_description', $this->selectedCity)
                            ->select('city_municipality_code')->first();
            $cityCode = $cityCode->getAttributes();
            $this->barangays = PhilippineBarangays::where('city_municipality_code', $cityCode['city_municipality_code'])->get();
        }

        if ($this->selectedProvince === '') {
            $this->cities = collect();
            $this->barangays = collect();
        }
        if ($this->selectedCity === '') {
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

    public function exportPDS()
    {
        try {
            $user = $this->selectedUser; // Fetch the selected user data

            // If no user is selected, throw an exception
            if (!$user) {
                throw new \Exception('No user selected.');
            }

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
            ];

            $pdf = Pdf::loadView('pdf.pds', ['pds' => $pds]);
            $pdf->setPaper('A4', 'portrait');
            
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, $user->userData->first_name . ' ' . $user->userData->surname . ' PDS.pdf');
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
