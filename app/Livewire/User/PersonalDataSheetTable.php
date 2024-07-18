<?php

namespace App\Livewire\User;

use App\Models\PhilippineBarangays;
use App\Models\PhilippineCities;
use App\Models\PhilippineProvinces;
use App\Models\User;
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

    // Educational Background
    public $education = [];

    // Eligibility
    public $eligibilities = [];

    // Work Experience
    public $workExperiences = [];


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

                $this->editSpouse = null;                                                
                $this->dispatch('notify', [
                    'message' => "Spouse's info updated successfully!", 
                    'type' => 'success'
                ]);
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

                $user->employeesFather->update([
                    'surname' => $this->father_surname,
                    'first_name' => $this->father_first_name,
                    'middle_name' => $this->father_middle_name,
                    'name_extension' => $this->father_name_extension,
                ]);                

                $this->editFather = null;                                                
                $this->dispatch('notify', [
                    'message' => "Father's name updated successfully!", 
                    'type' => 'success'
                ]);
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Father's name update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }

    public function saveChildren(){
        try{
            $user = Auth::user();
            if($user){
                foreach ($this->children as $child) {
                    $childRecord = $user->employeesChildren->find($child['id']);
                    if ($childRecord) {
                        $childRecord->update([
                            'childs_name' => $child['childs_name'],
                            'childs_birth_date' => $child['childs_birth_date'],
                        ]);
                    }
                }

                $this->editChildren = null;
                $this->dispatch('notify', [
                    'message' => "Children's info updated successfully!", 
                    'type' => 'success'
                ]);
            }
        }catch(Exception $e){
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

                $user->employeesMother->update([
                    'surname' => $this->mother_surname,
                    'first_name' => $this->mother_first_name,
                    'middle_name' => $this->mother_middle_name,
                    'name_extension' => $this->mother_name_extension,
                ]);                

                $this->editMother = null;                                                
                $this->dispatch('notify', [
                    'message' => "Mother's name updated successfully!", 
                    'type' => 'success'
                ]);
            }
        }catch(Exception $e){
            $this->dispatch('notify', [
                'message' => "Mother's name update was unsuccessful!", 
                'type' => 'error'
            ]);
            throw $e;
        }
    }

    public function saveEducationBackground(){
        try {
            $user = Auth::user();
            if ($user) {

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
                $this->dispatch('notify', [
                    'message' => "Education background updated successfully!",
                    'type' => 'success'
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Education background update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }

    public function saveEligibility(){
        try {
            $user = Auth::user();
            if ($user) {

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
                $this->dispatch('notify', [
                    'message' => "Eligibilities updated successfully!",
                    'type' => 'success'
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Eligibilities update was unsuccessful!",
                'type' => 'error'
            ]);
            throw $e;
        }
    }


}
