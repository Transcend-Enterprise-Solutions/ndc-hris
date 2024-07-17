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

    public $pprovinces;
    public $pcities;
    public $rcities;
    public $pbarangays;
    public $rbarangays;

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

            }
        }catch(Exception $e){
            throw $e;
        }
    }
}
