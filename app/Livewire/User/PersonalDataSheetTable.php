<?php

namespace App\Livewire\User;

use App\Models\AssOrgMemberships;
use App\Models\CharReferences;
use App\Models\Eligibility;
use App\Models\EmployeesChildren;
use App\Models\EmployeesEducation;
use App\Models\EmployeesFather;
use App\Models\EmployeesMother;
use App\Models\EmployeesSpouse;
use App\Models\Hobbies;
use App\Models\LearningAndDevelopment;
use App\Models\NonAcadDistinctions;
use App\Models\PhilippineBarangays;
use App\Models\PhilippineCities;
use App\Models\PhilippineProvinces;
use App\Models\Skills;
use App\Models\User;
use App\Models\VoluntaryWorks;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonalDataSheetTable extends Component
{
    public $pds;
    public $popup_message = '';
    public $pprovinces;
    public $pcities;
    public $rcities;
    public $pbarangays;
    public $rbarangays;
    public $editSpouse = false;
    public $editFather = false;
    public $editMother = false;
    public $editChildren = false;
    public $editEducBackground = false;
    public $editEligibility = false;
    public $editWorkExp = false;
    public $editVoluntaryWorks = false;
    public $editLearnDev = false;
    public $editSkills = false;
    public $editHobbies = false;
    public $editNonAcad = false;
    public $editMemberships = false;
    public $editReferences = false;
    public $addSpouse = false;
    public $addFather = false;
    public $addMother = false;
    public $addChildren = false;
    public $addEducBackground = false;
    public $addEligibility = false;
    public $addWorkExp = false;
    public $addVoluntaryWorks = false;
    public $addLearnDev = false;
    public $addSkills = false;
    public $addHobbies = false;
    public $addNonAcad = false;
    public $addMemberships = false;
    public $addReferences = false;
    public $delete = false;
    public $thisData;
    public $thisDataId;
    public $deleteMessage = "";

    // Personal Information
    public $personalInfo = false;
    public $surname;
    public $first_name;
    public $middle_name;
    public $name_extension;
    public $date_of_birth;
    public $place_of_birth;
    public $sex;
    public $civil_status;
    public $citizenship;
    public $height;
    public $weight;
    public $blood_type;
    public $mobile_number;
    public $tel_number;
    public $gsis;
    public $sss;
    public $pagibig;
    public $philhealth;
    public $tin;
    public $agency_employee_no;
    public $email;
    public $p_house_street;
    public $p_barangay;
    public $p_city;
    public $p_province;
    public $p_zipcode;
    public $r_house_street;
    public $r_barangay;
    public $r_city;
    public $r_province;
    public $r_zipcode;

    // Family Background
    public $spouse_surname;
    public $spouse_first_name;
    public $spouse_middle_name;
    public $spouse_date_of_birth;
    public $spouse_name_extension;
    public $spouse_occupation;
    public $spouse_employer;
    public $spouse_emp_business_address;
    public $spouse_emp_tel_num;
    public $father_surname;
    public $father_first_name;
    public $father_middle_name;
    public $father_name_extension;
    public $mother_surname;
    public $mother_first_name;
    public $mother_middle_name;
    public $mother_name_extension;
    public $children = [];
    public $newChildren = [];

    // Educational Background
    public $education = [];
    public $newEducation = [];

    // Eligibility
    public $eligibilities = [];
    public $newEligibilities = [];

    // Work Experience
    public $workExperiences = [];
    public $newWorkExperiences = [];

    // Voluntary Works
    public $voluntaryWork = [];
    public $newVoluntaryWorks = [];

    // Learning and Development
    public $learnAndDevs = [];
    public $newLearnAndDevs = [];

    // Skills
    public $mySkills = [];
    public $myNewSkills = [];

    // Hobbies
    public $myHobbies = [];
    public $myNewHobbies = [];

    // Non-Academic Distinction/Recognition
    public $nonAcads = [];
    public $newNonAcads = [];

    // Membership in Association/Organization
    public $memberships = [];
    public $newMemberships = [];

    // References
    public $myReferences = [];
    public $myNewReferences = [];


    public function render(){
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();

        $this->pds = [
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

        if($this->personalInfo === true){
            $this->getProvincesAndCities();
            if ($this->p_province != null) {
                $provinceCode = PhilippineProvinces::where('province_description', $this->p_province)
                                ->select('province_code')->first();
                $provinceCode = $provinceCode->getAttributes();
                $this->pcities = PhilippineCities::where('province_code', $provinceCode['province_code'])->get();
            }
    
            if ($this->r_province != null) {
                $provinceCode = PhilippineProvinces::where('province_description', $this->r_province)
                                ->select('province_code')->first();
                $provinceCode = $provinceCode->getAttributes();
                $this->rcities = PhilippineCities::where('province_code', $provinceCode['province_code'])->get();
            }
    
            if ($this->p_city != null) {
                $cityCode = PhilippineCities::where('city_municipality_description', $this->p_city)
                                ->select('city_municipality_code')->first();
                $cityCode = $cityCode->getAttributes();
                $this->pbarangays = PhilippineBarangays::where('city_municipality_code', $cityCode['city_municipality_code'])->get();
            }
    
            if ($this->r_city != null) {
                $cityCode = PhilippineCities::where('city_municipality_description', $this->r_city)
                                ->select('city_municipality_code')->first();
                $cityCode = $cityCode->getAttributes();
                $this->rbarangays = PhilippineBarangays::where('city_municipality_code', $cityCode['city_municipality_code'])->get();
            }    
        }

        return view('livewire.user.personal-data-sheet-table', [
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
        ]);
    }

    public function getProvincesAndCities(){
        $this->pprovinces = PhilippineProvinces::all();
        $this->pcities = collect();
        $this->rcities = collect();
        $this->pbarangays = collect();
        $this->rbarangays = collect();
    }

    public function exportPDS(){
        try{
            $pds = $this->pds;
            $pdf = Pdf::loadView('pdf.pds', ['pds' => $pds]);
            $pdf->setPaper('A4', 'portrait');
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, $pds['userData']->first_name . ' ' . $pds['userData']->surname . ' PDS.pdf');
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->addSpouse = null;
        $this->addFather = null;
        $this->addMother = null;
        $this->addChildren = null;
        $this->addEducBackground = null;
        $this->addEligibility = null;
        $this->addWorkExp = null;
        $this->addVoluntaryWorks = null;
        $this->addLearnDev = null;
        $this->addSkills = null;
        $this->addHobbies = null;
        $this->addNonAcad = null;
        $this->addMemberships = null;
        $this->addReferences = null;

        $this->newChildren = [];
        $this->newEducation = [];
        $this->newEligibilities = [];
        $this->newWorkExperiences = [];
        $this->newVoluntaryWorks = [];
        $this->myNewSkills = [];
        $this->myNewHobbies = [];
        $this->newLearnAndDevs = [];
        $this->newNonAcads = [];
        $this->newMemberships = [];
        $this->myNewReferences = [];

        $this->thisData = null;
        $this->thisDataId = null;
        $this->delete = null;
    }

    // Edit and Add Section ---------------------------------------------------------------------------- //

    // Edit ---------------------------------------------------------------------------- //

    public function toggleEditPersonalInfo(){
        $this->personalInfo = true;
        try{
            $this->surname = $this->pds['userData']->surname;
            $this->first_name = $this->pds['userData']->first_name;
            $this->middle_name = $this->pds['userData']->middle_name;
            $this->name_extension = $this->pds['userData']->name_extension;
            $this->date_of_birth = $this->pds['userData']->date_of_birth;
            $this->place_of_birth = $this->pds['userData']->place_of_birth;
            $this->sex = $this->pds['userData']->sex;
            $this->civil_status = $this->pds['userData']->civil_status;
            $this->citizenship = $this->pds['userData']->citizenship;
            $this->height = $this->pds['userData']->height;
            $this->weight = $this->pds['userData']->weight;
            $this->blood_type = $this->pds['userData']->blood_type;
            $this->mobile_number = $this->pds['userData']->mobile_number;
            $this->tel_number = $this->pds['userData']->tel_number;
            $this->gsis = $this->pds['userData']->gsis;
            $this->sss = $this->pds['userData']->sss;
            $this->pagibig = $this->pds['userData']->pagibig;
            $this->philhealth = $this->pds['userData']->philhealth;
            $this->tin = $this->pds['userData']->tin;
            $this->agency_employee_no = $this->pds['userData']->agency_employee_no;
            $this->email = $this->pds['userData']->email;
            $this->p_house_street = $this->pds['userData']->p_house_street;
            $this->p_zipcode = $this->pds['userData']->permanent_selectedZipcode;
            $this->p_province = $this->pds['userData']->permanent_selectedProvince;
            $this->p_city = $this->pds['userData']->permanent_selectedCity;
            $this->p_barangay = $this->pds['userData']->permanent_selectedBarangay;
            $this->r_house_street = $this->pds['userData']->r_house_street;
            $this->r_zipcode = $this->pds['userData']->residential_selectedZipcode;
            $this->r_province = $this->pds['userData']->residential_selectedProvince;
            $this->r_city = $this->pds['userData']->residential_selectedCity;
            $this->r_barangay = $this->pds['userData']->residential_selectedBarangay;
            
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditSpouse(){
        $this->editSpouse = true;
        try{
            $this->spouse_surname = $this->pds['userSpouse']->surname;
            $this->spouse_first_name = $this->pds['userSpouse']->first_name;
            $this->spouse_middle_name = $this->pds['userSpouse']->middle_name;
            $this->spouse_date_of_birth = $this->pds['userSpouse']->birth_date;
            $this->spouse_name_extension = $this->pds['userSpouse']->name_extension;
            $this->spouse_occupation = $this->pds['userSpouse']->occupation;
            $this->spouse_employer = $this->pds['userSpouse']->employer;
            $this->spouse_emp_business_address = $this->pds['userSpouse']->business_address;
            $this->spouse_emp_tel_num = $this->pds['userSpouse']->tel_number;
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditFather(){
        $this->editFather = true;
        try{
            $this->father_surname = $this->pds['userFather']->surname;
            $this->father_first_name = $this->pds['userFather']->first_name;
            $this->father_middle_name = $this->pds['userFather']->middle_name;
            $this->father_name_extension = $this->pds['userFather']->name_extension;
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditMother(){
        $this->editMother = true;
        try{
            $this->mother_surname = $this->pds['userMother']->surname;
            $this->mother_first_name = $this->pds['userMother']->first_name;
            $this->mother_middle_name = $this->pds['userMother']->middle_name;
            $this->mother_name_extension = $this->pds['userMother']->name_extension;
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditChildren(){
        $this->editChildren = true;
        try{
            $this->children = $this->pds['userChildren']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditEducBackground(){
        $this->editEducBackground = true;
        try{
            $this->education = $this->pds['educBackground']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditEligibility(){
        $this->editEligibility = true;
        try{
            $this->eligibilities = $this->pds['eligibility']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditWorkExp(){
        $this->editWorkExp = true;
        try{
            $this->workExperiences = $this->pds['workExperience']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditVoluntaryWorks(){
        $this->editVoluntaryWorks = true;
        try{
            $this->voluntaryWork = $this->pds['voluntaryWorks']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditLearnAndDev(){
        $this->editLearnDev = true;
        try{
            $this->learnAndDevs = $this->pds['lds']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditSkills(){
        $this->editSkills = true;
        try{
            $this->mySkills = $this->pds['skills']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditHobbies(){
        $this->editHobbies = true;
        try{
            $this->myHobbies = $this->pds['hobbies']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditNonAcads(){
        $this->editNonAcad = true;
        try{
            $this->nonAcads = $this->pds['non_acads_distinctions']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditMemberships(){
        $this->editMemberships = true;
        try{
            $this->memberships = $this->pds['assOrgMemberships']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }
    public function toggleEditReferences(){
        $this->editReferences = true;
        try{
            $this->myReferences = $this->pds['references']->toArray();
        }catch(Exception $e){
            throw $e;
        }
    }

    // Add ---------------------------------------------------------------------------- //

    public function toggleAddSpouse(){
        $this->editSpouse = true;
        $this->addSpouse = true;
    }
    public function toggleAddFather(){
        $this->editFather = true;
        $this->addFather = true;
    }
    public function toggleAddMother(){
        $this->editMother = true;
        $this->addMother = true;
    }
    public function toggleAddChildren(){
        $this->editChildren = true;
        $this->addChildren = true;
        $this->newChildren[] = [
            'childs_name' => '', 
            'childs_birth_date' => ''
        ];
    }
    public function toggleAddEducBackground(){
        $this->editEducBackground = true;
        $this->addEducBackground = true;
        $this->newEducation[] = [
            'level' => '', 
            'name_of_school' => '',
            'basic_educ_degree_course' => '',
            'from' => '',
            'to' => '',
            'highest_level_unit_earned' => '',
            'year_graduated' => '',
            'award' => '',
        ];
    }
    public function toggleAddEligibility(){
        $this->editEligibility = true;
        $this->addEligibility = true;
        $this->newEligibilities[] = [
            'eligibility' => '', 
            'rating' => '',
            'date' => '',
            'place_of_exam' => '',
            'license' => '',
            'date_of_validity' => '',
        ];
    }
    public function toggleAddWorkExp(){
        $this->editWorkExp = true;
        $this->addWorkExp = true;
        $this->newWorkExperiences[] = [
            'start_date' => '', 
            'end_date' => '',
            'position' => '',
            'department' => '',
            'monthly_salary' => '',
            'status_of_appointment' => '',
            'gov_service' => '',
        ];
    }
    public function toggleAddVoluntaryWorks(){
        $this->editVoluntaryWorks = true;
        $this->addVoluntaryWorks = true;
        $this->newVoluntaryWorks[] = [
            'org_name' => '', 
            'org_address' => '',
            'start_date' => '',
            'end_date' => '',
            'no_of_hours' => '',
            'position_nature' => '',
        ];
    }
    public function toggleAddLearnAndDev(){
        $this->editLearnDev = true;
        $this->addLearnDev = true;
        $this->newLearnAndDevs[] = [
            'title' => '', 
            'start_date' => '',
            'end_date' => '',
            'no_of_hours' => '',
            'type_of_ld' => '',
            'conducted_by' => '',
        ];
    }
    public function toggleAddSkills(){
        $this->editSkills = true;
        $this->addSkills = true;
        $this->myNewSkills[] = [
            'skill' => '',
        ];
    }
    public function toggleAddHobbies(){
        $this->editHobbies = true;
        $this->addHobbies = true;
        $this->myNewHobbies[] = [
            'hobby' => '',
        ];
    }
    public function toggleAddNonAcads(){
        $this->editNonAcad = true;
        $this->addNonAcad = true;
        $this->newNonAcads[] = [
            'award' => '',
            'ass_org_name	' => '',
            'date_received' => '',
        ];
    }
    public function toggleAddMemberships(){
        $this->editMemberships = true;
        $this->addMemberships = true;
        $this->newMemberships[] = [
            'ass_org_name	' => '',
            'position' => '',
        ];
    }
    public function toggleAddReferences(){
        $this->editReferences = true;
        $this->addReferences = true;
        $this->myNewReferences[] = [
            'firstname	' => '',
            'middle_name' => '',
            'surname' => '',
            'address' => '',
            'tel_number' => '',
            'mobile_number' => '',
        ];
    }

    // Add ---------------------------------------------------------------------------- //

    public function toggleDelete($data , $id){
        $this->thisData = $data;
        $this->thisDataId = $id;
        switch($data){
            case "spouse":
                $this->deleteMessage = "spouse's info";
                break;
            case "father":
                $this->deleteMessage = "father's info";
                break;
            case "mother":
                $this->deleteMessage = "mother's info";
                break;
            case "child":
                $this->deleteMessage = "child's info";
                break;
            case "educ":
                $this->deleteMessage = "educational background";
                break;
            case "elig":
                $this->deleteMessage = "civil service eligibility";
                break;
            case "exp":
                $this->deleteMessage = "work experience";
                break;
            case "voluntary":
                $this->deleteMessage = "voluntary work";
                break;
            case "ld":
                $this->deleteMessage = "training";
                break;
            case "nonacad":
                $this->deleteMessage = "non-academic distinction/recognition";
                break;
            case "membership":
                $this->deleteMessage = "membership in association/organization";
                break;
            case "refs":
                $this->deleteMessage = "character reference";
                break;
            default:
                break;
        }
        $this->delete = true;
    }

    // -------------------------------------------------------------------------------------------------- //

    public function savePersonalInfo(){
        try{
            $user = Auth::user();
            if($user){

                $this->validate([
                    'surname' => 'required|string|max:255',
                    'first_name' => 'required|string|max:255',
                    'middle_name' => 'nullable|string|max:255',
                    'name_extension' => 'nullable|string|max:10',
                    'date_of_birth' => 'required|date',
                    'place_of_birth' => 'required|string|max:255',
                    'sex' => 'required|in:Male,Female',
                    'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
                    'citizenship' => 'required|string|max:255',
                    'height' => 'required|numeric|min:0|max:300',
                    'weight' => 'required|numeric|min:0|max:500',
                    'blood_type' => 'required|string|in:A,B,AB,O',
                    'mobile_number' => 'required|string|max:15',
                    'tel_number' => 'required|string|max:15',
                    'gsis' => 'required|string|max:50',
                    'sss' => 'required|string|max:50',
                    'pagibig' => 'required|string|max:50',
                    'philhealth' => 'required|string|max:50',
                    'tin' => 'required|string|max:50',
                    'agency_employee_no' => 'required|string|max:50',
                    'email' => 'required|email|max:255',
                    'p_house_street' => 'required|string|max:255',
                    'p_barangay' => 'required|string|max:255',
                    'p_city' => 'required|string|max:255',
                    'p_province' => 'required|string|max:255',
                    'p_zipcode' => 'required|numeric|digits:4',
                    'r_house_street' => 'required|string|max:255',
                    'r_barangay' => 'required|string|max:255',
                    'r_city' => 'required|string|max:255',
                    'r_province' => 'required|string|max:255',
                    'r_zipcode' => 'required|numeric|digits:4',
                ]);

                $user->userData->update([
                    'surname' => $this->surname,
                    'first_name' => $this->first_name,
                    'middle_name' => $this->middle_name,
                    'name_extension' => $this->name_extension,
                    'date_of_birth' => $this->date_of_birth,
                    'place_of_birth' => $this->place_of_birth,
                    'sex' => $this->sex,
                    'civil_status' => $this->civil_status,
                    'citizenship' => $this->citizenship,
                    'height' => $this->height,
                    'weight' => $this->weight,
                    'blood_type' => $this->blood_type,
                    'mobile_number' => $this->mobile_number,
                    'tel_number' => $this->tel_number,
                    'gsis' => $this->gsis,
                    'sss' => $this->sss,
                    'pagibig' => $this->pagibig,
                    'philhealth' => $this->philhealth,
                    'tin' => $this->tin,
                    'agency_employee_no' => $this->agency_employee_no,
                    'email' => $this->email,
                    'p_house_street' => $this->p_house_street,
                    'permanent_selectedBarangay' => $this->p_barangay,
                    'permanent_selectedCity' => $this->p_city,
                    'permanent_selectedProvince' => $this->p_province,
                    'permanent_selectedZipcode' => $this->p_zipcode,
                    'r_house_street' => $this->r_house_street,
                    'residential_selectedBarangay' => $this->r_barangay,
                    'residential_selectedCity' => $this->r_city,
                    'residential_selectedProvince' => $this->r_province,
                    'residential_selectedZipcode' => $this->r_zipcode,
                ]);                

                $this->personalInfo = null;                                                
                $this->dispatch('notify', [
                    'message' => 'Personal Information updated successfully!', 
                    'type' => 'success'
                ]);
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => 'Personal Information update was unsuccessful!', 
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function saveSpouse(){
        try{
            $user = Auth::user();
            if($user){

                $this->validate([
                    'spouse_surname' => 'required|string|max:255',
                    'spouse_first_name' => 'required|string|max:255',
                    'spouse_date_of_birth' => 'required|date',
                ]);

                if($this->addSpouse != true){
                    $user->employeesSpouse->update([
                        'surname' => $this->spouse_surname,
                        'first_name' => $this->spouse_first_name,
                        'middle_name' => $this->spouse_middle_name,
                        'name_extension' => $this->spouse_name_extension,
                        'birth_date' => $this->spouse_date_of_birth,
                        'occupation' => $this->spouse_occupation,
                        'employer' => $this->spouse_employer,
                        'business_address' => $this->spouse_emp_business_address,
                        'tel_number' => $this->spouse_emp_tel_num,
                    ]); 
                    
                    $this->dispatch('notify', [
                        'message' => "Spouse's info updated successfully!", 
                        'type' => 'success'
                    ]);
                }else{
                    EmployeesSpouse::create([
                        'user_id' => $user->id,
                        'surname' => $this->spouse_surname,
                        'first_name' => $this->spouse_first_name,
                        'middle_name' => $this->spouse_middle_name,
                        'name_extension' => $this->spouse_name_extension,
                        'birth_date' => $this->spouse_date_of_birth,
                        'occupation' => $this->spouse_occupation,
                        'employer' => $this->spouse_employer,
                        'business_address' => $this->spouse_emp_business_address,
                        'tel_number' => $this->spouse_emp_tel_num,
                    ]);

                    $this->dispatch('notify', [
                        'message' => "Spouse's info added successfully!", 
                        'type' => 'success'
                    ]); 
                }

                $this->editSpouse = null;
                $this->addSpouse = null;                                                
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Spouse's info update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function saveFather(){
        try{
            $user = Auth::user();
            if($user){

                $this->validate([
                    'father_surname' => 'required|string|max:255',
                    'father_first_name' => 'required|string|max:255',
                ]);

                if($this->addFather != true){
                    $user->employeesFather->update([
                        'surname' => $this->father_surname,
                        'first_name' => $this->father_first_name,
                        'middle_name' => $this->father_middle_name,
                        'name_extension' => $this->father_name_extension,
                    ]);  
                    $this->dispatch('notify', [
                        'message' => "Father's name updated successfully!", 
                        'type' => 'success'
                    ]);           
                }else{
                    EmployeesFather::create([
                        'user_id' => $user->id,
                        'surname' => $this->father_surname,
                        'first_name' => $this->father_first_name,
                        'middle_name' => $this->father_middle_name,
                        'name_extension' => $this->father_name_extension,
                    ]);
                    $this->dispatch('notify', [
                        'message' => "Father's name added successfully!", 
                        'type' => 'success'
                    ]);
                }

                $this->editFather = null;                                                
                $this->addFather = null;
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Father's name update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewChild(){
        $this->newChildren[] = [
            'childs_name' => '', 
            'childs_birth_date' => ''
        ];
    }
    public function removeNewChild($index){
        unset($this->newChildren[$index]);
        $this->newChildren = array_values($this->newChildren);
    }
    public function saveChildren(){
        try{
            $user = Auth::user();
            if($user){

                if($this->addChildren != true){
                    $this->validate([
                        'children.*.childs_name' => 'required|string|max:255',
                        'children.*.childs_birth_date' => 'required|date',
                    ]);

                    foreach ($this->children as $child) {
                        $childRecord = $user->employeesChildren->find($child['id']);
                        if ($childRecord) {
                            $childRecord->update([
                                'childs_name' => $child['childs_name'],
                                'childs_birth_date' => $child['childs_birth_date'],
                            ]);
                        }
                    }
                    $this->dispatch('notify', [
                        'message' => "Children's info updated successfully!", 
                        'type' => 'success'
                    ]);
                    $this->editChildren = null;
                    $this->addChildren = null;
                    $this->newChildren = [];
                }else{
                    $this->validate([
                        'newChildren.*.childs_name' => 'required|string|max:255',
                        'newChildren.*.childs_birth_date' => 'required|date',
                    ]);

                    foreach ($this->newChildren as $child) {
                        EmployeesChildren::create([
                            'user_id' => $user->id,
                            'childs_name' => $child['childs_name'],
                            'childs_birth_date' => $child['childs_birth_date'],
                        ]);
                    }
                    $this->dispatch('notify', [
                        'message' => "Children's info added successfully!", 
                        'type' => 'success'
                    ]);
                    $this->editChildren = null;
                    $this->addChildren = null;
                    $this->newChildren = [];
                }
            }
        }catch(Exception $e){
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Children's info update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function saveMother(){
        try{
            $user = Auth::user();
            if($user){

                $this->validate([
                    'mother_surname' => 'required|string|max:255',
                    'mother_first_name' => 'required|string|max:255',
                ]);

                if($this->addMother != true){
                    $user->employeesMother->update([
                        'surname' => $this->mother_surname,
                        'first_name' => $this->mother_first_name,
                        'middle_name' => $this->mother_middle_name,
                        'name_extension' => $this->mother_name_extension,
                    ]);      
                    $this->dispatch('notify', [
                        'message' => "Mother's name updated successfully!", 
                        'type' => 'success'
                    ]);          
                }else{
                    EmployeesMother::create([
                        'user_id' => $user->id,
                        'surname' => $this->mother_surname,
                        'first_name' => $this->mother_first_name,
                        'middle_name' => $this->mother_middle_name,
                        'name_extension' => $this->mother_name_extension,
                    ]);  
                    $this->dispatch('notify', [
                        'message' => "Mother's name added successfully!", 
                        'type' => 'success'
                    ]);
                }

                $this->editMother = null;                                                
                $this->addMother = null;                                                   
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Mother's name update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewEducation(){
        $this->newEducation[] = [
            'level' => '', 
            'name_of_school' => '',
            'basic_educ_degree_course' => '',
            'from' => '',
            'to' => '',
            'highest_level_unit_earned' => '',
            'year_graduated' => '',
            'award' => '',
        ];
    }
    public function removeNewEducation($index){
        unset($this->newEducation[$index]);
        $this->newEducation = array_values($this->newEducation);
    }
    public function saveEducationBackground(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addEducBackground != true){
                    $this->validate([
                        'newEducation.*.level_code' => 'required|string|max:255',
                        'newEducation.*.name_of_school' => 'required|string',
                        'newEducation.*.from' => 'required|numeric',
                        'newEducation.*.to' => 'required|numeric',
                        'newEducation.*.year_graduated' => 'required|numeric',
                    ]);
                    foreach ($this->education as $educ) {
                        $educRecord = $user->employeesEducation->find($educ['id']);
                        if ($educRecord) {
                            $educRecord->update([
                                'level' => $educ['level'],
                                'name_of_school' => $educ['name_of_school'],
                                'from' => $educ['from'],
                                'to' => $educ['to'],
                                'basic_educ_degree_course' => $educ['basic_educ_degree_course'],
                                'award' => $educ['award'],
                                'highest_level_unit_earned' => $educ['highest_level_unit_earned'],
                                'year_graduated' => $educ['year_graduated'],
                            ]);
                        }
                    }
                    
                    $this->editEducBackground = null;
                    $this->addEducBackground = null;
                    $this->newEducation = [];
                    $this->dispatch('notify', [
                        'message' => "Education background updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newEducation.*.level_code' => 'required|string|max:255',
                        'newEducation.*.name_of_school' => 'required|string',
                        'newEducation.*.from' => 'required|numeric',
                        'newEducation.*.to' => 'required|numeric',
                        'newEducation.*.year_graduated' => 'required|numeric',
                    ]);
                    foreach ($this->newEducation as $educ) {
                        $level = '';
                        switch($educ['level_code']){
                            case 1:
                                $level = 'Elementary';
                                break;
                            case 2:
                                $level = 'Secondary';
                                break;
                            case 3:
                                $level = 'Vocational/Trade Course';
                                break;
                            case 4:
                                $level = 'College';
                                break;
                            case 5:
                                $level = 'Graduate Studies';
                                break;
                            default:
                                break;
                        }

                        EmployeesEducation::create([
                            'user_id' => $user->id,
                            'level_code' => $educ['level_code'],
                            'level' => $level,
                            'name_of_school' => $educ['name_of_school'],
                            'from' => $educ['from'],
                            'to' => $educ['to'],
                            'basic_educ_degree_course' => $educ['basic_educ_degree_course'],
                            'award' => $educ['award'],
                            'highest_level_unit_earned' => $educ['highest_level_unit_earned'],
                            'year_graduated' => $educ['year_graduated'],
                        ]);
                    }
                    
                    $this->editEducBackground = null;
                    $this->addEducBackground = null;
                    $this->newEducation = [];
                    $this->dispatch('notify', [
                        'message' => "Education background added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Education background update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewEligibility(){
        $this->newEligibilities[] = [
            'eligibility' => '', 
            'rating' => '',
            'date' => '',
            'place_of_exam' => '',
            'license' => '',
            'date_of_validity' => '',
        ];
    }
    public function removeNewEligibility($index){
        unset($this->newEligibilities[$index]);
        $this->newEligibilities = array_values($this->newEligibilities);
    }
    public function saveEligibility(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addEligibility != true){
                    $this->validate([
                        'eligibilities.*.eligibility' => 'required|string|max:255',
                        'eligibilities.*.rating' => 'required|numeric',
                        'eligibilities.*.date' => 'required|date',
                        'eligibilities.*.place_of_exam' => 'required|string',
                    ]);

                    foreach ($this->eligibilities as $elig) {
                        $eligRecord = $user->eligibility->find($elig['id']);
                        if ($eligRecord) {
                            $eligRecord->update([
                                'eligibility' => $elig['eligibility'],
                                'rating' => $elig['rating'],
                                'date' => $elig['date'],
                                'place_of_exam' => $elig['place_of_exam'],
                                'license' => $elig['license'],
                                'date_of_validity' => $elig['date_of_validity'],
                            ]);
                        }
                    }
                    $this->editEligibility = null;
                    $this->addEligibility = null;
                    $this->dispatch('notify', [
                        'message' => "Eligibilities updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newEligibilities.*.eligibility' => 'required|string|max:255',
                        'newEligibilities.*.rating' => 'required|numeric',
                        'newEligibilities.*.date' => 'required|date',
                        'newEligibilities.*.place_of_exam' => 'required|string',
                    ]);
                    foreach ($this->newEligibilities as $elig) {
                        Eligibility::create([
                            'user_id' => $user->id,
                            'eligibility' => $elig['eligibility'],
                            'rating' => $elig['rating'],
                            'date' => $elig['date'],
                            'place_of_exam' => $elig['place_of_exam'],
                            'license' => $elig['license'],
                            'date_of_validity' => $elig['date_of_validity'],
                        ]);
                    }
                    $this->editEligibility = null;
                    $this->addEligibility = null;
                    $this->newEligibilities = [];
                    $this->dispatch('notify', [
                        'message' => "Eligibilities added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Eligibilities update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewWorkExp(){
        $this->newWorkExperiences[] = [
            'start_date' => '', 
            'end_date' => '',
            'position' => '',
            'department' => '',
            'monthly_salary' => '',
            'status_of_appointment' => '',
            'gov_service' => '',
        ];
    }
    public function removeNewWorkExp($index){
        unset($this->newWorkExperiences[$index]);
        $this->newWorkExperiences = array_values($this->newWorkExperiences);
    }
    public function saveWorkExp(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addWorkExp != true){
                    $this->validate([
                        'workExperiences.*.department' => 'required|string|max:255',
                        'workExperiences.*.monthly_salary' => 'required|numeric',
                        'workExperiences.*.start_date' => 'required|date',
                        'workExperiences.*.end_date' => 'required|date',
                        'workExperiences.*.status_of_appointment' => 'required|string',
                        'workExperiences.*.gov_service' => 'required',
                    ]);

                    foreach ($this->workExperiences as $exp) {
                        $expRecord = $user->workExperience->find($exp['id']);
                        if ($expRecord) {
                            $expRecord->update([
                                'start_date' => $exp['start_date'],
                                'end_date' => $exp['end_date'],
                                'position' => $exp['position'],
                                'department' => $exp['department'],
                                'monthly_salary' => $exp['monthly_salary'],
                                'status_of_appointment' => $exp['status_of_appointment'],
                                'gov_service' => $exp['gov_service'],
                            ]);
                        }
                    }
                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->dispatch('notify', [
                        'message' => "Work Experience updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newWorkExperiences.*.department' => 'required|string|max:255',
                        'newWorkExperiences.*.monthly_salary' => 'required|numeric',
                        'newWorkExperiences.*.start_date' => 'required|date',
                        'newWorkExperiences.*.end_date' => 'required|date',
                        'newWorkExperiences.*.status_of_appointment' => 'required|string',
                        'newWorkExperiences.*.gov_service' => 'required',
                    ]);

                    foreach ($this->newWorkExperiences as $exp) {
                        WorkExperience::create([
                            'user_id' => $user->id,
                            'start_date' => $exp['start_date'],
                            'end_date' => $exp['end_date'],
                            'position' => $exp['position'],
                            'department' => $exp['department'],
                            'monthly_salary' => $exp['monthly_salary'],
                            'status_of_appointment' => $exp['status_of_appointment'],
                            'gov_service' => $exp['gov_service'],
                        ]);
                    }
                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->newWorkExperiences = [];
                    $this->dispatch('notify', [
                        'message' => "Work Experience added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Work Experience update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewVoluntaryWork(){
        $this->newVoluntaryWorks[] = [
            'org_name' => '', 
            'org_address' => '',
            'start_date' => '',
            'end_date' => '',
            'no_of_hours' => '',
            'position_nature' => '',
        ];
    }
    public function removeNewVoluntaryWork($index){
        unset($this->newVoluntaryWorks[$index]);
        $this->newVoluntaryWorks = array_values($this->newVoluntaryWorks);
    }
    public function saveVoluntaryWork(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addVoluntaryWorks != true){
                    $this->validate([
                        'voluntaryWork.*.org_name' => 'required|string|max:255',
                        'voluntaryWork.*.org_address' => 'required|string|max:255',
                        'voluntaryWork.*.no_of_hours' => 'required|numeric',
                        'voluntaryWork.*.start_date' => 'required|date',
                        'voluntaryWork.*.end_date' => 'required|date',
                        'voluntaryWork.*.position_nature' => 'required|string',
                    ]);

                    foreach ($this->voluntaryWork as $work) {
                        $workRecord = $user->voluntaryWorks->find($work['id']);
                        if ($workRecord) {
                            $workRecord->update([
                                'start_date' => $work['start_date'],
                                'end_date' => $work['end_date'],
                                'org_name' => $work['org_name'],
                                'org_address' => $work['org_address'],
                                'no_of_hours' => $work['no_of_hours'],
                                'position_nature' => $work['position_nature'],
                            ]);
                        }
                    }
                    $this->editVoluntaryWorks = null;
                    $this->addVoluntaryWorks = null;
                    $this->dispatch('notify', [
                        'message' => "Voluntary Works updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newVoluntaryWorks.*.org_name' => 'required|string|max:255',
                        'newVoluntaryWorks.*.org_address' => 'required|string|max:255',
                        'newVoluntaryWorks.*.no_of_hours' => 'required|numeric',
                        'newVoluntaryWorks.*.start_date' => 'required|date',
                        'newVoluntaryWorks.*.end_date' => 'required|date',
                        'newVoluntaryWorks.*.position_nature' => 'required|string',
                    ]);

                    foreach ($this->newVoluntaryWorks as $work) {
                        VoluntaryWorks::create([
                            'user_id' => $user->id,
                            'start_date' => $work['start_date'],
                            'end_date' => $work['end_date'],
                            'org_name' => $work['org_name'],
                            'org_address' => $work['org_address'],
                            'no_of_hours' => $work['no_of_hours'],
                            'position_nature' => $work['position_nature'],
                        ]);
                    }
                    $this->editVoluntaryWorks = null;
                    $this->addVoluntaryWorks = null;
                    $this->dispatch('notify', [
                        'message' => "Voluntary Works added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Voluntary Works update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewLearnAndDev(){
        $this->newLearnAndDevs[] = [
            'title' => '', 
            'start_date' => '',
            'end_date' => '',
            'no_of_hours' => '',
            'type_of_ld' => '',
            'conducted_by' => '',
        ];
    }
    public function removeNewLearnAndDev($index){
        unset($this->newLearnAndDevs[$index]);
        $this->newLearnAndDevs = array_values($this->newLearnAndDevs);
    }
    public function saveLearnAndDev(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addLearnDev != true){
                    $this->validate([
                        'learnAndDevs.*.title' => 'required|string|max:255',
                        'learnAndDevs.*.type_of_ld' => 'required|string|max:255',
                        'learnAndDevs.*.no_of_hours' => 'required|numeric',
                        'learnAndDevs.*.start_date' => 'required|date',
                        'learnAndDevs.*.end_date' => 'required|date',
                        'learnAndDevs.*.conducted_by' => 'required|string',
                    ]);
    
                    foreach ($this->learnAndDevs as $ld) {
                        $ldRecord = $user->learningAndDevelopment->find($ld['id']);
                        if ($ldRecord) {
                            $ldRecord->update([
                                'start_date' => $ld['start_date'],
                                'end_date' => $ld['end_date'],
                                'title' => $ld['title'],
                                'type_of_ld' => $ld['type_of_ld'],
                                'no_of_hours' => $ld['no_of_hours'],
                                'conducted_by' => $ld['conducted_by'],
                            ]);
                        }
                    }
                    $this->editLearnDev = null;
                    $this->addLearnDev = null;
                    $this->dispatch('notify', [
                        'message' => "Learning and Development updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newLearnAndDevs.*.title' => 'required|string|max:255',
                        'newLearnAndDevs.*.type_of_ld' => 'required|string|max:255',
                        'newLearnAndDevs.*.no_of_hours' => 'required|numeric',
                        'newLearnAndDevs.*.start_date' => 'required|date',
                        'newLearnAndDevs.*.end_date' => 'required|date',
                        'newLearnAndDevs.*.conducted_by' => 'required|string',
                    ]);
    
                    foreach ($this->newLearnAndDevs as $ld) {
                        LearningAndDevelopment::create([
                            'user_id' => $user->id,
                            'start_date' => $ld['start_date'],
                            'end_date' => $ld['end_date'],
                            'title' => $ld['title'],
                            'type_of_ld' => $ld['type_of_ld'],
                            'no_of_hours' => $ld['no_of_hours'],
                            'conducted_by' => $ld['conducted_by'],
                        ]);
                    }
                    $this->editLearnDev = null;
                    $this->addLearnDev = null;
                    $this->newLearnAndDevs = [];
                    $this->dispatch('notify', [
                        'message' => "Learning and Development added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Learning and Development update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewSkill(){
        $this->myNewSkills[] = [
            'skill' => '',
        ];
    }
    public function removeNewSkill($index){
        unset($this->myNewSkills[$index]);
        $this->myNewSkills = array_values($this->myNewSkills);
    }
    public function deleteSkill($id, $index){
        unset($this->mySkills[$index]);
        $this->mySkills = array_values($this->mySkills);
        $user = Auth::user();
        $skill = $user->skills->find($id);
        if ($skill) {
            $skill->delete();
        }
    }
    public function saveSkills(){
        try {
            $user = Auth::user();
            if ($user) {
                if($this->addSkills != true){
                    $this->validate([
                        'mySkills.*.skill' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->mySkills as $skill) {
                        $skillRecord = $user->skills->find($skill['id']);
                        if ($skillRecord) {
                            $skillRecord->update([
                                'skill' => $skill['skill'],
                            ]);
                        }
                    }
    
                    $this->editSkills = null;
                    $this->addSkills = null;
                    $this->dispatch('notify', [
                        'message' => "Skills updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'myNewSkills.*.skill' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->myNewSkills as $skill) {
                        Skills::create([
                            'user_id' => $user->id,
                            'skill' => $skill['skill'],
                        ]);
                    }
    
                    $this->editSkills = null;
                    $this->addSkills = null;
                    $this->myNewSkills = [];
                    $this->dispatch('notify', [
                        'message' => "Skills added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Skills update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewHobby(){
        $this->myNewHobbies[] = [
            'hobby' => '',
        ];
    }
    public function removeNewHobby($index){
        unset($this->myNewHobbies[$index]);
        $this->myNewHobbies = array_values($this->myNewHobbies);
    }
    public function deleteHobby($id, $index){
        unset($this->myHobbies[$index]);
        $this->myHobbies = array_values($this->myHobbies);
        $user = Auth::user();
        $hobby = $user->hobbies->find($id);
        if ($hobby) {
            $hobby->delete();
        }
    }
    public function saveHobbies(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addHobbies != true){
                    $this->validate([
                        'myHobbies.*.hobby' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->myHobbies as $hobby) {
                        $hobbyRecord = $user->hobbies->find($hobby['id']);
                        if ($hobbyRecord) {
                            $hobbyRecord->update([
                                'hobby' => $hobby['hobby'],
                            ]);
                        }
                    }
    
                    $this->editHobbies = null;
                    $this->addHobbies = null;
                    $this->dispatch('notify', [
                        'message' => "Hobbies updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'myNewHobbies.*.hobby' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->myNewHobbies as $hobby) {
                        Hobbies::create([
                            'user_id' => $user->id,
                            'hobby' => $hobby['hobby'],
                        ]);
                    }
    
                    $this->editHobbies = null;
                    $this->addHobbies = null;
                    $this->myNewHobbies = [];
                    $this->dispatch('notify', [
                        'message' => "Hobbies added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Hobbies update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewNonAcad(){
        $this->newNonAcads[] = [
            'award' => '',
            'ass_org_name	' => '',
            'date_received' => '',
        ];
    }
    public function removeNewNonAcad($index){
        unset($this->newNonAcads[$index]);
        $this->newNonAcads = array_values($this->newNonAcads);
    }
    public function saveNonAcad(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addNonAcad != true){
                    $this->validate([
                        'nonAcads.*.award' => 'required|string|max:255',
                        'nonAcads.*.ass_org_name' => 'required|string|max:255',
                        'nonAcads.*.date_received' => 'required|date',
                    ]);
    
                    foreach ($this->nonAcads as $nonAcad) {
                        $nonAcadRecord = $user->nonAcadDistinctions->find($nonAcad['id']);
                        if ($nonAcadRecord) {
                            $nonAcadRecord->update([
                                'award' => $nonAcad['award'],
                                'ass_org_name' => $nonAcad['ass_org_name'],
                                'date_received' => $nonAcad['date_received'],
                            ]);
                        }
                    }
    
                    $this->editNonAcad = null;
                    $this->addNonAcad = null;
                    $this->dispatch('notify', [
                        'message' => "Non-Academic Distinction/Recognition updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newNonAcads.*.award' => 'required|string|max:255',
                        'newNonAcads.*.ass_org_name' => 'required|string|max:255',
                        'newNonAcads.*.date_received' => 'required|date',
                    ]);
    
                    foreach ($this->newNonAcads as $nonAcad) {
                        NonAcadDistinctions::create([
                            'user_id' => $user->id,
                            'award' => $nonAcad['award'],
                            'ass_org_name' => $nonAcad['ass_org_name'],
                            'date_received' => $nonAcad['date_received'],
                        ]);
                    }
    
                    $this->editNonAcad = null;
                    $this->addNonAcad = null;
                    $this->newNonAcads = [];
                    $this->dispatch('notify', [
                        'message' => "Non-Academic Distinction/Recognition added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Non-Academic Distinction/Recognition update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewMembership(){
        $this->newMemberships[] = [
            'ass_org_name	' => '',
            'position' => '',
        ];
    }
    public function removeNewMembership($index){
        unset($this->newMemberships[$index]);
        $this->newMemberships = array_values($this->newMemberships);
    }
    public function saveMemberships(){
        try {
            $user = Auth::user();
            if ($user) {
                if($this->addMemberships != true){
                    $this->validate([
                        'memberships.*.position' => 'required|string|max:255',
                        'memberships.*.ass_org_name' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->memberships as $member) {
                        $memberRecord = $user->assOrgMembership->find($member['id']);
                        if ($memberRecord) {
                            $memberRecord->update([
                                'ass_org_name' => $member['ass_org_name'],
                                'position' => $member['position'],
                            ]);
                        }
                    }
    
                    $this->editMemberships = null;
                    $this->addMemberships = null;
                    $this->dispatch('notify', [
                        'message' => "Membership in Association/Organization updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'newMemberships.*.position' => 'required|string|max:255',
                        'newMemberships.*.ass_org_name' => 'required|string|max:255',
                    ]);
    
                    foreach ($this->newMemberships as $member) {
                        AssOrgMemberships::create([
                            'user_id' => $user->id,
                            'ass_org_name' => $member['ass_org_name'],
                            'position' => $member['position'],
                        ]);
                    }
    
                    $this->editMemberships = null;
                    $this->addMemberships = null;
                    $this->newMemberships = [];
                    $this->dispatch('notify', [
                        'message' => "Membership in Association/Organization added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Membership in Association/Organization update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewReference(){
        $this->myNewReferences[] = [
            'firstname	' => '',
            'middle_name' => '',
            'surname' => '',
            'address' => '',
            'tel_number' => '',
            'mobile_number' => '',
        ];
    }
    public function removeNewReference($index){
        unset($this->myNewReferences[$index]);
        $this->myNewReferences = array_values($this->myNewReferences);
    }
    public function saveReferences(){
        try {
            $user = Auth::user();
            if ($user) {

                if($this->addReferences != true){
                    $this->validate([
                        'myReferences.*.firstname' => 'required|string|max:255',
                        'myReferences.*.middle_initial' => 'required|string|max:255',
                        'myReferences.*.surname' => 'required|string|max:255',
                        'myReferences.*.address' => 'required|string|max:255',
                        'myReferences.*.mobile_number' => 'required|numeric',
                    ]);
    
                    foreach ($this->myReferences as $refs) {
                        $refsRecord = $user->charReferences->find($refs['id']);
                        if ($refsRecord) {
                            $refsRecord->update([
                                'firstname' => $refs['firstname'],
                                'middle_initial' => $refs['middle_initial'],
                                'surname' => $refs['surname'],
                                'address' => $refs['address'],
                                'tel_number' => $refs['tel_number'],
                                'mobile_number' => $refs['mobile_number'],
                            ]);
                        }
                    }
                    
                    $this->editReferences = null;
                    $this->addReferences = null;
                    $this->dispatch('notify', [
                        'message' => "Character References updated successfully!",
                        'type' => 'success'
                    ]);
                }else{
                    $this->validate([
                        'myNewReferences.*.firstname' => 'required|string|max:255',
                        'myNewReferences.*.middle_initial' => 'required|string|max:255',
                        'myNewReferences.*.surname' => 'required|string|max:255',
                        'myNewReferences.*.address' => 'required|string|max:255',
                        'myNewReferences.*.mobile_number' => 'required|numeric',
                    ]);
    
                    foreach ($this->myNewReferences as $refs) {
                        CharReferences::create([
                            'user_id' => $user->id,
                            'firstname' => $refs['firstname'],
                            'middle_initial' => $refs['middle_initial'],
                            'surname' => $refs['surname'],
                            'address' => $refs['address'],
                            'tel_number' => $refs['tel_number'],
                            'mobile_number' => $refs['mobile_number'],
                        ]);
                    }
                    
                    $this->editReferences = null;
                    $this->addReferences = null;
                    $this->dispatch('notify', [
                        'message' => "Character References added successfully!",
                        'type' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('notify', [
                'message' => "Character References update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }

    // End of Edit and Add Section ---------------------------------------------------------------------- //

    public function deleteData(){
        try{
            $user = Auth::user();
            if($user){
                $message = "";
                switch($this->thisData){
                    case "spouse":
                        $message = "Spouse's info";
                        $user->employeesSpouse->delete();
                        break;
                    case "father":
                        $message = "Father's info";
                        $user->employeesFather->delete();
                        break;
                    case "mother":
                        $message = "Mother's info";
                        $user->employeesMother->delete();
                        break;
                    case "child":
                        $message = "Child's info";
                        $child = $user->employeesChildren->find($this->thisDataId);
                        if ($child) {
                            $child->delete();
                        }
                        break;
                    case "educ":
                        $message = "Educational background";
                        $educ = $user->employeesEducation->find($this->thisDataId);
                        if ($educ) {
                            $educ->delete();
                        }
                        break;
                    case "elig":
                        $message = "Civil service eligibility";
                        $elig = $user->eligibility->find($this->thisDataId);
                        if ($elig) {
                            $elig->delete();
                        }
                        break;
                    case "exp":
                        $message = "Work experience";
                        $exp = $user->workExperience->find($this->thisDataId);
                        if ($exp) {
                            $exp->delete();
                        }
                        break;
                    case "voluntary":
                        $message = "Voluntary work";
                        $voluntary = $user->voluntaryWorks->find($this->thisDataId);
                        if ($voluntary) {
                            $voluntary->delete();
                        }
                        break;
                    case "ld":
                        $message = "Training";
                        $ld = $user->learningAndDevelopment->find($this->thisDataId);
                        if ($ld) {
                            $ld->delete();
                        }
                        break;
                    case "nonacad":
                        $message = "Non-Academic distinction/recognition";
                        $nonacad = $user->nonAcadDistinctions->find($this->thisDataId);
                        if ($nonacad) {
                            $nonacad->delete();
                        }
                        break;
                    case "membership":
                        $message = "Membership in association/organization";
                        $membership = $user->assOrgMembership->find($this->thisDataId);
                        if ($membership) {
                            $membership->delete();
                        }
                        break;
                    case "refs":
                        $message = "Character reference";
                        $refs = $user->charReferences->find($this->thisDataId);
                        if ($refs) {
                            $refs->delete();
                        }
                        break;
                    default:
                        break;
                }

                $this->delete = null;
                $this->dispatch('notify', [
                    'message' => $message . " deleted successfully!",
                    'type' => 'success'
                ]);
                $this->thisData = null;
                $this->thisDataId = null;
                $this->deleteMessage = null;
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Deletion was unsuccessful!",
                'type' => 'success'
            ]);
            throw $e;
        }
    }
}