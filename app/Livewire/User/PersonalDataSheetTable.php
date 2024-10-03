<?php

namespace App\Livewire\User;

use App\Exports\PDSExport;
use App\Models\AssOrgMemberships;
use App\Models\CharReferences;
use App\Models\Countries;
use App\Models\Eligibility;
use App\Models\EmployeesChildren;
use App\Models\EmployeesEducation;
use App\Models\EmployeesFather;
use App\Models\EmployeesMother;
use App\Models\EmployeesSpouse;
use App\Models\Hobbies;
use App\Models\LearningAndDevelopment;
use App\Models\NonAcadDistinctions;
use App\Models\PdsC4Answers;
use App\Models\PdsGovIssuedId;
use App\Models\PdsPhoto;
use App\Models\PhilippineBarangays;
use App\Models\PhilippineCities;
use App\Models\PhilippineProvinces;
use App\Models\Skills;
use App\Models\VoluntaryWorks;
use App\Models\WorkExperience;
use App\Models\ESignature;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;

class PersonalDataSheetTable extends Component
{
    use WithFileUploads;

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
    public $p_house_number;
    public $p_street;
    public $p_subdivision;
    public $r_house_number;
    public $r_street;
    public $r_subdivision;

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

    // C4 Section
    public $qKey;
    public $editAnswer = [
        'q34a' => false,
        'q34b' => false,
        'q35a' => false,
        'q35b' => false,
        'q36a' => false,
        'q37a' => false,
        'q38a' => false,
        'q38b' => false,
        'q39a' => false,
        'q40a' => false,
        'q40b' => false,
        'q40c' => false,
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
    public $countries;
    public $dual_citizenship_type;
    public $dual_citizenship_country;

    // E-Signature
    public $e_signature;
    public $temporaryUrl;

    public function mount(){
        $this->getC4Answers();
        $this->countries = Countries::all();
    }

    public function render(){

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

        $user = Auth::user();
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
            'pds_c4_answers' => $user->pdsC4Answers,
            'pds_gov_id' => $user->pdsGovIssuedId,
        ];

        $pdsGovId = PdsGovIssuedId::where('user_id', $user->id)->first();
        if($pdsGovId){
            $this->govId = $pdsGovId->gov_id;
            $this->idNumber = $pdsGovId->id_number;
            $this->dateIssued = $pdsGovId->date_of_issuance;
        }

        $eSignature = ESignature::where('user_id', $user->id)->first();

        return view('livewire.user.personal-data-sheet-table', [
            'userData' => $this->pds['userData'],
            'userSpouse' => $this->pds['userSpouse'],
            'userMother' => $this->pds['userMother'],
            'userFather' => $this->pds['userFather'],
            'userChildren' => $this->pds['userChildren'],
            'educBackground' => $this->pds['educBackground'],
            'eligibility' => $this->pds['eligibility'],
            'workExperience' => $this->pds['workExperience'],
            'voluntaryWorks' => $this->pds['voluntaryWorks'],
            'lds' => $this->pds['lds'],
            'skills' => $this->pds['skills'],
            'hobbies' => $this->pds['hobbies'],
            'non_acads_distinctions' => $this->pds['non_acads_distinctions'],
            'assOrgMemberships' => $this->pds['assOrgMemberships'],
            'references' => $this->pds['references'],
            'eSignature' => $eSignature,
        ]);
    }

    public function getProvincesAndCities(){
        $this->pprovinces = PhilippineProvinces::all();
        $this->pcities = collect();
        $this->rcities = collect();
        $this->pbarangays = collect();
        $this->rbarangays = collect();
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
        $user = Auth::user();
        return PdsC4Answers::where('user_id', $user->id)
            ->where('question_number', $qNum)
            ->where('question_letter', $qLetter)
            ->first();
    }    

    public function exportPDS(){
        try {
            $exporter = new PDSExport($this->pds);
            $result = $exporter->export();

            return response()->streamDownload(function () use ($result) {
                echo $result['content'];
            }, $result['filename']);
        } catch (Exception $e) {
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
        $this->myReferences = [];
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
            $p_address_line1 = explode(",", $this->p_house_street);
            $r_address_line1 = explode(",", $this->r_house_street);
            $this->p_house_number = $p_address_line1[0];
            $this->p_street = $p_address_line1[1];
            $this->p_subdivision = $p_address_line1[2];
            $this->r_house_number = $r_address_line1[0];
            $this->r_street = $r_address_line1[1];
            $this->r_subdivision = $r_address_line1[2];
            $this->dual_citizenship_type = $this->pds['userData']->dual_citizenship_type;
            $this->dual_citizenship_country =$this->pds['userData']->dual_citizenship_country;
            
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
            'toPresent' => '',
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
            'toPresent' => '',
            'position' => '',
            'department' => '',
            'monthly_salary' => '',
            'status_of_appointment' => '',
            'gov_service' => '',
            'sg_step' => '',
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
            'toPresent' => '',
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
            'toPresent' => '',
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

    public function savePersonalInfo(){
        try{
            $user = Auth::user();
            if($user){

                $this->p_house_street = ($this->p_house_number ?: 'N/A') . ',' . ($this->p_street ?: 'N/A') . ',' . ($this->p_subdivision ?: 'N/A');
                $this->r_house_street = ($this->r_house_number ?: 'N/A') . ',' . ($this->r_street ?: 'N/A') . ',' . ($this->r_subdivision ?: 'N/A');
                if($this->citizenship === 'Dual Citizenship'){
                    $this->validate([
                        'dual_citizenship_type' => 'required',
                        'dual_citizenship_country' => 'required',
                    ]);
                }else{
                    $this->dual_citizenship_type = null;
                    $this->dual_citizenship_country = null;
                }

                $this->validate([
                    'surname' => 'required|string|max:255',
                    'first_name' => 'required|string|max:255',
                    'middle_name' => 'nullable|string|max:255',
                    'name_extension' => 'nullable|string|max:10',
                    'date_of_birth' => 'required|date',
                    'place_of_birth' => 'required|string|max:255',
                    'sex' => 'required|in:Male,Female',
                    'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
                    'citizenship' => 'required',
                    'height' => 'required|numeric|min:0|max:300',
                    'weight' => 'required|numeric|min:0|max:500',
                    'blood_type' => 'required|string|in:A,B,AB,O',
                    'mobile_number' => 'required|string|max:15',
                    'gsis' => 'required|string|max:50',
                    'sss' => 'required|string|max:50',
                    'pagibig' => 'required|string|max:50',
                    'philhealth' => 'required|string|max:50',
                    'tin' => 'required|string|max:50',
                    'agency_employee_no' => 'required|string|max:50',
                    'email' => 'email|max:255',
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
                    'dual_citizenship_type' => $this->dual_citizenship_type ?: null,
                    'dual_citizenship_country' => $this->dual_citizenship_country ?: null,
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
                $this->dispatch('swal', [
                    'title' => 'Personal Information updated successfully!', 
                    'icon' => 'success'
                ]);
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => 'Personal Information update was unsuccessful!', 
                'icon' => 'error'
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
                    // 'spouse_date_of_birth' => 'required|date',
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
                    
                    $this->dispatch('swal', [
                        'title' => "Spouse's info updated successfully!", 
                        'icon' => 'success'
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

                    $this->dispatch('swal', [
                        'title' => "Spouse's info added successfully!", 
                        'icon' => 'success'
                    ]); 
                }

                $this->editSpouse = null;
                $this->addSpouse = null;                                                
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Spouse's info update was unsuccessful!", 
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Father's name updated successfully!", 
                        'icon' => 'success'
                    ]);           
                }else{
                    EmployeesFather::create([
                        'user_id' => $user->id,
                        'surname' => $this->father_surname,
                        'first_name' => $this->father_first_name,
                        'middle_name' => $this->father_middle_name,
                        'name_extension' => $this->father_name_extension,
                    ]);
                    $this->dispatch('swal', [
                        'title' => "Father's name added successfully!", 
                        'icon' => 'success'
                    ]);
                }

                $this->editFather = null;                                                
                $this->addFather = null;
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Father's name update was unsuccessful!", 
                'icon' => 'error'
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
                    ]);

                    foreach ($this->children as $child) {
                        $childRecord = $user->employeesChildren->find($child['id']);
                        if ($childRecord) {
                            $childRecord->update([
                                'childs_name' => $child['childs_name'],
                                'childs_birth_date' => $child['childs_birth_date'] ?: null,
                            ]);
                        }
                    }
                    $this->dispatch('swal', [
                        'title' => "Children's info updated successfully!", 
                        'icon' => 'success'
                    ]);
                    $this->editChildren = null;
                    $this->addChildren = null;
                    $this->newChildren = [];
                }else{
                    $this->validate([
                        'newChildren.*.childs_name' => 'required|string|max:255',
                        // 'newChildren.*.childs_birth_date' => 'required|date',
                    ]);

                    foreach ($this->newChildren as $child) {
                        EmployeesChildren::create([
                            'user_id' => $user->id,
                            'childs_name' => $child['childs_name'],
                            'childs_birth_date' => $child['childs_birth_date'] ?: null,
                        ]);
                    }
                    $this->dispatch('swal', [
                        'title' => "Children's info added successfully!", 
                        'icon' => 'success'
                    ]);
                    $this->editChildren = null;
                    $this->addChildren = null;
                    $this->newChildren = [];
                }
            }
        }catch(Exception $e){
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Children's info update was unsuccessful!", 
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Mother's name updated successfully!", 
                        'icon' => 'success'
                    ]);          
                }else{
                    EmployeesMother::create([
                        'user_id' => $user->id,
                        'surname' => $this->mother_surname,
                        'first_name' => $this->mother_first_name,
                        'middle_name' => $this->mother_middle_name,
                        'name_extension' => $this->mother_name_extension,
                    ]);  
                    $this->dispatch('swal', [
                        'title' => "Mother's name added successfully!", 
                        'icon' => 'success'
                    ]);
                }

                $this->editMother = null;                                                
                $this->addMother = null;                                                   
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Mother's name update was unsuccessful!", 
                'icon' => 'error'
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
                    foreach ($this->education as $index => $educ) {
                        $validationRules = [
                            'education.'.$index.'.level_code' => 'required|numeric',
                            'education.'.$index.'.name_of_school' => 'required|string',
                            'education.'.$index.'.from' => 'required|date',
                        ];
                
                        if (!$educ['toPresent']) {
                            $validationRules['education.'.$index.'.to'] = 'required|date';
                            $validationRules['education.'.$index.'.year_graduated'] = 'required|numeric';
                            $educ['toPresent'] = null;
                        } else {
                            $validationRules['education.'.$index.'.toPresent'] = 'required';
                            $educ['toPresent'] = 'Present';
                            $educ['to'] = null;
                        }
                
                        $this->validate($validationRules);

                        $educRecord = $user->employeesEducation->find($educ['id']);
                        if ($educRecord) {
                            $educRecord->update([
                                'level' => $educ['level'],
                                'name_of_school' => $educ['name_of_school'],
                                'from' => $educ['from'],
                                'to' => $educ['to'] ?: null,
                                'toPresent' => $educ['toPresent'] ?: null,
                                'basic_educ_degree_course' => $educ['basic_educ_degree_course'],
                                'award' => $educ['award'],
                                'highest_level_unit_earned' => $educ['highest_level_unit_earned'],
                                'year_graduated' => $educ['year_graduated'] ?: null,
                            ]);
                        }
                    }
                    
                    $this->editEducBackground = null;
                    $this->addEducBackground = null;
                    $this->newEducation = [];
                    $this->dispatch('swal', [
                        'title' => "Education background updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    foreach ($this->newEducation as $index => $educ) {
                        $validationRules = [
                            'newEducation.'.$index.'.level_code' => 'required|numeric',
                            'newEducation.'.$index.'.name_of_school' => 'required|string',
                            'newEducation.'.$index.'.from' => 'required|date',
                        ];
                
                        if (!$educ['toPresent']) {
                            $validationRules['newEducation.'.$index.'.to'] = 'required|date';
                            $validationRules['newEducation.'.$index.'.year_graduated'] = 'required|numeric';
                            $educ['toPresent'] = null;
                        } else {
                            $validationRules['newEducation.'.$index.'.toPresent'] = 'required';
                            $educ['toPresent'] = 'Present';
                            $educ['to'] = null;
                        }
                
                        $this->validate($validationRules);

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
                            'to' => $educ['to'] ?: null,
                            'toPresent' => $educ['toPresent'] ?: null,
                            'basic_educ_degree_course' => $educ['basic_educ_degree_course'],
                            'award' => $educ['award'],
                            'highest_level_unit_earned' => $educ['highest_level_unit_earned'],
                            'year_graduated' => $educ['year_graduated'] ?: null,
                        ]);
                    }
                    
                    $this->editEducBackground = null;
                    $this->addEducBackground = null;
                    $this->newEducation = [];
                    $this->dispatch('swal', [
                        'title' => "Education background added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
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
                    $this->dispatch('swal', [
                        'title' => "Eligibilities updated successfully!",
                        'icon' => 'success'
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
                    $this->dispatch('swal', [
                        'title' => "Eligibilities added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Eligibilities update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewWorkExp(){
        $this->newWorkExperiences[] = [
            'start_date' => '', 
            'end_date' => '',
            'toPresent' => '',
            'position' => '',
            'department' => '',
            'monthly_salary' => '',
            'status_of_appointment' => '',
            'gov_service' => '',
            'sg_step' => '',
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
                    foreach ($this->workExperiences as $index => $exp) {
                        $validationRules = [
                            'workExperiences.'.$index.'.department' => 'required|string',
                            'workExperiences.'.$index.'.monthly_salary' => 'required|string',
                            'workExperiences.'.$index.'.start_date' => 'required|date',
                            'workExperiences.'.$index.'.gov_service' => 'required',
                            'workExperiences.'.$index.'.status_of_appointment' => 'required|string',
                        ];
                
                        if (!$exp['toPresent']) {
                            $validationRules['workExperiences.'.$index.'.end_date'] = 'required|date';
                            $exp['toPresent'] = null;
                        } else {
                            $validationRules['workExperiences.'.$index.'.toPresent'] = 'required';
                            $exp['toPresent'] = 'Present';
                            $exp['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        $expRecord = $user->workExperience->find($exp['id']);
                        if ($expRecord) {
                            $expRecord->update([
                                'start_date' => $exp['start_date'],
                                'end_date' => $exp['end_date'] ?: null,
                                'toPresent' => $exp['toPresent'] ?: null,
                                'position' => $exp['position'],
                                'department' => $exp['department'],
                                'monthly_salary' => $exp['monthly_salary'],
                                'sg_step' => $exp['sg_step'],
                                'status_of_appointment' => $exp['status_of_appointment'],
                                'gov_service' => $exp['gov_service'],
                            ]);
                        }
                    }
                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->dispatch('swal', [
                        'title' => "Work Experience updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    foreach ($this->newWorkExperiences as $index => $exp) {
                        $validationRules = [
                            'newWorkExperiences.'.$index.'.department' => 'required|numeric',
                            'newWorkExperiences.'.$index.'.monthly_salary' => 'required|string',
                            'newWorkExperiences.'.$index.'.start_date' => 'required|date',
                            'newWorkExperiences.'.$index.'.gov_service' => 'required',
                            'newWorkExperiences.'.$index.'.status_of_appointment' => 'required|string',
                        ];
                
                        if (!$exp['toPresent']) {
                            $validationRules['newWorkExperiences.'.$index.'.end_date'] = 'required|date';
                            $exp['toPresent'] = null;
                        } else {
                            $validationRules['newWorkExperiences.'.$index.'.toPresent'] = 'required';
                            $exp['toPresent'] = 'Present';
                            $exp['end_date'] = null;
                        }

                        WorkExperience::create([
                            'user_id' => $user->id,
                            'start_date' => $exp['start_date'],
                            'end_date' => $exp['end_date'] ?: null,
                            'toPresent' => $exp['toPresent'] ?: null,
                            'position' => $exp['position'],
                            'department' => $exp['department'],
                            'monthly_salary' => $exp['monthly_salary'],
                            'sg_step' => $exp['sg_step'],
                            'status_of_appointment' => $exp['status_of_appointment'],
                            'gov_service' => $exp['gov_service'],
                        ]);
                    }
                    $this->editWorkExp = null;
                    $this->addWorkExp = null;
                    $this->newWorkExperiences = [];
                    $this->dispatch('swal', [
                        'title' => "Work Experience added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Work Experience update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewVoluntaryWork(){
        $this->newVoluntaryWorks[] = [
            'org_name' => '', 
            'org_address' => '',
            'start_date' => '',
            'toPresent' => '',
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
                    foreach ($this->voluntaryWork as $index => $work) {
                        $validationRules = [
                            'voluntaryWork.'.$index.'.org_name' => 'required|string',
                            'voluntaryWork.'.$index.'.org_address' => 'required|string',
                            'voluntaryWork.'.$index.'.no_of_hours' => 'required|numeric',
                            'voluntaryWork.'.$index.'.start_date' => 'required|date',
                            'voluntaryWork.'.$index.'.position_nature' => 'required|string',
                        ];
                
                        if (!$work['toPresent']) {
                            $validationRules['voluntaryWork.'.$index.'.end_date'] = 'required|date';
                            $work['toPresent'] = null;
                        } else {
                            $validationRules['voluntaryWork.'.$index.'.toPresent'] = 'required';
                            $work['toPresent'] = 'Present';
                            $work['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        $workRecord = $user->voluntaryWorks->find($work['id']);
                        if ($workRecord) {
                            $workRecord->update([
                                'start_date' => $work['start_date'],
                                'end_date' => $work['end_date'] ?: null,
                                'toPresent' => $work['toPresent'] ?: null,
                                'org_name' => $work['org_name'],
                                'org_address' => $work['org_address'],
                                'no_of_hours' => $work['no_of_hours'],
                                'position_nature' => $work['position_nature'],
                            ]);
                        }
                    }
                    $this->editVoluntaryWorks = null;
                    $this->addVoluntaryWorks = null;
                    $this->dispatch('swal', [
                        'title' => "Voluntary Works updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    foreach ($this->newVoluntaryWorks as $index => $work) {
                        $validationRules = [
                            'newVoluntaryWorks.'.$index.'.org_name' => 'required|string',
                            'newVoluntaryWorks.'.$index.'.org_address' => 'required|string',
                            'newVoluntaryWorks.'.$index.'.no_of_hours' => 'required|numeric',
                            'newVoluntaryWorks.'.$index.'.start_date' => 'required|date',
                            'newVoluntaryWorks.'.$index.'.position_nature' => 'required|string',
                        ];
                
                        if (!$work['toPresent']) {
                            $validationRules['newVoluntaryWorks.'.$index.'.end_date'] = 'required|date';
                            $work['toPresent'] = null;
                        } else {
                            $validationRules['newVoluntaryWorks.'.$index.'.toPresent'] = 'required';
                            $work['toPresent'] = 'Present';
                            $work['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        VoluntaryWorks::create([
                            'user_id' => $user->id,
                            'start_date' => $work['start_date'],
                            'end_date' => $work['end_date'],
                            'toPresent' => $work['toPresent'],
                            'org_name' => $work['org_name'],
                            'org_address' => $work['org_address'],
                            'no_of_hours' => $work['no_of_hours'],
                            'position_nature' => $work['position_nature'],
                        ]);
                    }
                    $this->editVoluntaryWorks = null;
                    $this->addVoluntaryWorks = null;
                    $this->dispatch('swal', [
                        'title' => "Voluntary Works added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Voluntary Works update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }
    public function addNewLearnAndDev(){
        $this->newLearnAndDevs[] = [
            'title' => '', 
            'start_date' => '',
            'end_date' => '',
            'toPresent' => '',
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
                    foreach ($this->learnAndDevs as $index => $ld) {
                        $validationRules = [
                            'learnAndDevs.'.$index.'.title' => 'required|string',
                            'learnAndDevs.'.$index.'.type_of_ld' => 'required|string',
                            'learnAndDevs.'.$index.'.no_of_hours' => 'required|numeric',
                            'learnAndDevs.'.$index.'.start_date' => 'required|date',
                            'learnAndDevs.'.$index.'.conducted_by' => 'required|string',
                        ];
                
                        if (!$ld['toPresent']) {
                            $validationRules['learnAndDevs.'.$index.'.end_date'] = 'required|date';
                            $ld['toPresent'] = null;
                        } else {
                            $validationRules['learnAndDevs.'.$index.'.toPresent'] = 'required';
                            $ld['toPresent'] = 'Present';
                            $ld['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        $ldRecord = $user->learningAndDevelopment->find($ld['id']);
                        if ($ldRecord) {
                            $ldRecord->update([
                                'start_date' => $ld['start_date'],
                                'end_date' => $ld['end_date'] ?: null,
                                'toPresent' => $ld['toPresent'] ?: null,
                                'title' => $ld['title'],
                                'type_of_ld' => $ld['type_of_ld'],
                                'no_of_hours' => $ld['no_of_hours'],
                                'conducted_by' => $ld['conducted_by'],
                            ]);
                        }
                    }
                    $this->editLearnDev = null;
                    $this->addLearnDev = null;
                    $this->dispatch('swal', [
                        'title' => "Learning and Development updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    foreach ($this->newLearnAndDevs as $index => $ld) {
                        $validationRules = [
                            'newLearnAndDevs.'.$index.'.title' => 'required|string',
                            'newLearnAndDevs.'.$index.'.type_of_ld' => 'required|string',
                            'newLearnAndDevs.'.$index.'.no_of_hours' => 'required|numeric',
                            'newLearnAndDevs.'.$index.'.start_date' => 'required|date',
                            'newLearnAndDevs.'.$index.'.conducted_by' => 'required|string',
                        ];
                
                        if (!$ld['toPresent']) {
                            $validationRules['newLearnAndDevs.'.$index.'.end_date'] = 'required|date';
                            $ld['toPresent'] = null;
                        } else {
                            $validationRules['newLearnAndDevs.'.$index.'.toPresent'] = 'required';
                            $ld['toPresent'] = 'Present';
                            $ld['end_date'] = null;
                        }
                
                        $this->validate($validationRules);

                        LearningAndDevelopment::create([
                            'user_id' => $user->id,
                            'start_date' => $ld['start_date'],
                            'end_date' => $ld['end_date'] ?: null,
                            'toPresent' => $ld['toPresent'] ?: null,
                            'title' => $ld['title'],
                            'type_of_ld' => $ld['type_of_ld'],
                            'no_of_hours' => $ld['no_of_hours'],
                            'conducted_by' => $ld['conducted_by'],
                        ]);
                    }
                    $this->editLearnDev = null;
                    $this->addLearnDev = null;
                    $this->newLearnAndDevs = [];
                    $this->dispatch('swal', [
                        'title' => "Learning and Development added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Learning and Development update was unsuccessful!",
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Skills updated successfully!",
                        'icon' => 'success'
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
                    $this->dispatch('swal', [
                        'title' => "Skills added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Skills update was unsuccessful!",
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Hobbies updated successfully!",
                        'icon' => 'success'
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
                    $this->dispatch('swal', [
                        'title' => "Hobbies added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Hobbies update was unsuccessful!",
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Non-Academic Distinction/Recognition updated successfully!",
                        'icon' => 'success'
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
                    $this->dispatch('swal', [
                        'title' => "Non-Academic Distinction/Recognition added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Non-Academic Distinction/Recognition update was unsuccessful!",
                'icon' => 'error'
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
                    $this->dispatch('swal', [
                        'title' => "Membership in Association/Organization updated successfully!",
                        'icon' => 'success'
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
                    $this->dispatch('swal', [
                        'title' => "Membership in Association/Organization added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Membership in Association/Organization update was unsuccessful!",
                'icon' => 'error'
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
            $charRefs = CharReferences::where("user_id", $user->id)->get();
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
                    $this->myReferences = [];
                    $this->myNewReferences = [];
                    $this->dispatch('swal', [
                        'title' => "Character References updated successfully!",
                        'icon' => 'success'
                    ]);
                }else{
                    if(count($charRefs) >= 3){
                        $this->editReferences = null;
                        $this->addReferences = null;
                        $this->myReferences = [];
                        $this->myNewReferences = [];
                        $this->dispatch('swal', [
                            'title' => "Character references are only up to 3 persons.",
                            'icon' => 'error'
                        ]);
                        return;
                    }

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
                    $this->myReferences = [];
                    $this->myNewReferences = [];
                    $this->dispatch('swal', [
                        'title' => "Character References added successfully!",
                        'icon' => 'success'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->resetValidation();
            $this->dispatch('swal', [
                'title' => "Character References update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    // C4 Edit Section  --------------------------------------------------------------------------------- //

    public function editC4Question($questionKey){
        $this->qKey = $questionKey;
        $this->editAnswer[$questionKey] = true;
    }
    
    public function cancelEditC4Question($questionKey){
        $this->editAnswer[$questionKey] = false;
        $this->qKey = null;
        $this->q34aAnswer = null;
        $this->q34bAnswer = null;
        $this->q34bDetails = null;
        $this->q35aAnswer = null;
        $this->q35aDetails = null;
        $this->q35bAnswer = null;
        $this->q35bDate_filed = null;
        $this->q35bStatus = null;
        $this->q36aAnswer = null;
        $this->q36aDetails = null;
        $this->q37aAnswer = null;
        $this->q37aDetails = null;
        $this->q38aAnswer = null;
        $this->q38aDetails = null;
        $this->q38bAnswer = null;
        $this->q38bDetails = null;
        $this->q39aAnswer = null;
        $this->q39aDetails = null;
        $this->q40aAnswer = null;
        $this->q40aDetails = null;
        $this->q40bAnswer = null;
        $this->q40bDetails = null;
        $this->q40cAnswer = null;
        $this->q40cDetails = null;
    }

    public function saveC4Question($qNum, $qLetter, $qAnswerVar = null, $qDetailsVar = null){
        try{
            $user = Auth::user();
            if($user){
                $qAnswer = $this->{$qAnswerVar};
                $qDetails = $qDetailsVar ? $this->{$qDetailsVar} : null;

                $dateFiled = null;
                $status = null;

                if($qAnswer == null){
                    $this->dispatch('swal', [
                        'title' => "Select an answer!",
                        'icon' => 'error'
                    ]);
                    return;
                }

                if($qNum == 35 && $qLetter == 'b' && $qAnswer == 1 && ($this->q35bDate_filed == null || $this->q35bStatus == null)){
                    $this->dispatch('swal', [
                        'title' => "Please add date filed and/or status of case/s!",
                        'icon' => 'error'
                    ]);
                    return;
                }

                if($qAnswer == 1 && $qDetails == null && $qNum != 35 && $qLetter != "a"){
                    $this->dispatch('swal', [
                        'title' => "Please add details!",
                        'icon' => 'error'
                    ]);
                    return;
                }

                if($qNum == 35 && $qLetter == "b"){
                    $dateFiled = $this->q35bDate_filed;
                    $status = $this->q35bStatus;
                }

                if(!$qAnswer){
                    $this->{$qDetailsVar} = null;
                    $qDetails = null;
                }

                $message = "";
                $pdsC4Answers = PdsC4Answers::where('user_id', $user->id)
                            ->where('question_number', $qNum)
                            ->where('question_letter', $qLetter)
                            ->first();
                if($pdsC4Answers){
                    $pdsC4Answers->update([
                        'answer' => $qAnswer,
                        'details' => $qDetails,
                        'date_filed' => $dateFiled,
                        'status' => $status,
                    ]);
                    $message = "Answer updated successfully!";
                }else{
                    PdsC4Answers::create([
                        'user_id' => $user->id,
                        'question_number' => $qNum,
                        'question_letter' => $qLetter,
                        'answer' => $qAnswer,
                        'details' => $qDetails,
                        'date_filed' => $dateFiled,
                        'status' => $status,
                    ]);
                    $message = "Answer added successfully!";
                }

                $this->dispatch('swal', [
                    'title' => $message,
                    'icon' => 'success'
                ]);
                $this->editAnswer[$this->qKey] = false;
                $this->qKey = null;
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Update was unsuccessfull!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function toggleEditGovId(){
        if($this->editGovId){
            $this->editGovId = null;
            // $this->govId = null;
            // $this->idNumber = null;
            // $this->dateIssued = null;
        }else{
            $this->editGovId = true;
        }
    }

    public function saveGovId(){
        try{
            $user = Auth::user();
            if($user){
                $message = "";
                $govId = PdsGovIssuedId::where('user_id', $user->id)->first();
                $this->validate([
                    'govId' => 'required',
                    'idNumber' => 'required',
                    'dateIssued' => 'required',
                ]);
                if($govId){
                    $govId->update([
                        'gov_id' => $this->govId,
                        'id_number' => $this->idNumber,
                        'date_of_issuance' => $this->dateIssued,
                    ]);
                    $message = "Government Issued ID updated successfully!";
                }else{
                    PdsGovIssuedId::create([
                        'user_id' => $user->id,
                        'gov_id' => $this->govId,
                        'id_number' => $this->idNumber,
                        'date_of_issuance' => $this->dateIssued,
                    ]);
                    $message = "Government Issued ID added successfully!";
                }
                $this->dispatch('swal', [
                    'title' => $message,
                    'icon' => 'success'
                ]);
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Update was unsuccessfull!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function savePhoto(){
        try {
            $message = "";
            $user = Auth::user();            
            if ($user && $this->pdsPhoto instanceof UploadedFile){
                $originalFilename = $this->pdsPhoto->getClientOriginalName();
                $uniqueFilename = time() . '_' . $originalFilename;
                $photo = PdsPhoto::where('user_id', $user->id)->first();
                $pathToDelete = "";
                if($photo){
                    $pathToDelete = str_replace('public/', '', $photo->photo);
                }
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
                $filePath = $this->pdsPhoto->storeAs('pds-photos', $uniqueFilename, 'public');
                if($photo){
                    $photo->update([
                        'photo' => 'public/' . $filePath,
                    ]);
                    $message = "Signature updated successfully!";
                }else{
                    PdsPhoto::create([
                        'user_id' => $user->id,
                        'photo' => 'public/' . $filePath,
                    ]);
                    $message = "Signature added successfully!";
                }

            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => "success",
            ]);
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Update was unsuccessfull!",
                'icon' => 'error'
            ]);
           throw $e;
        }
    }

    // End of Edit and Add Section ---------------------------------------------------------------------- //

    public function toggleDelete($data , $id = null){
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
            case "photo":
                $this->deleteMessage = "photo";
                break;
            default:
                break;
        }
        $this->delete = true;
    }

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
                    case "photo":
                        $message = "Photo";
                        $user->pdsPhoto->delete();
                        break;
                    default:
                        break;
                }

                $this->delete = null;
                $this->dispatch('swal', [
                    'title' => $message . " deleted successfully!",
                    'icon' => 'success'
                ]);
                $this->thisData = null;
                $this->thisDataId = null;
                $this->deleteMessage = null;
            }
        }catch(Exception $e){
            $this->dispatch('swal', [
                'title' => "Deletion was unsuccessful!",
                'icon' => 'success'
            ]);
            throw $e;
        }
    }

    public function updatedESignature()
    {
        // Generate a temporary URL for the selected image
        if ($this->e_signature) {
            $this->temporaryUrl = $this->e_signature->temporaryUrl();
        }
    }

    public function uploadSignature()
    {
        $this->validate([
            'e_signature' => 'image|max:1024', // 1MB Max
        ]);
    
        // Get the existing e-signature record for the user
        $existingSignature = ESignature::where('user_id', Auth::id())->first();

        // If the user already has an e-signature, delete the old file
        if ($existingSignature && Storage::disk('public')->exists($existingSignature->file_path)) {
            Storage::disk('public')->delete($existingSignature->file_path);
        }

        // Store the new uploaded image
        $originalFilename = $this->e_signature->getClientOriginalName();

        // Store the uploaded image with its original name (or custom name)
        $filePath = $this->e_signature->storeAs('signatures', $originalFilename, 'public');

        // Update or create the user's e-signature record with the new file
        ESignature::updateOrCreate(
            ['user_id' => Auth::id()], // Find the signature by user_id
            ['file_path' => $filePath] // Update or create the file_path
        );

        $this->e_signature = null;
        $this->temporaryUrl = null;
    
        // Set a success message
        $this->dispatch('swal', [
            'title' => "E-Signature uploaded successfully!",
            'icon' => 'success'
        ]);
    }
}