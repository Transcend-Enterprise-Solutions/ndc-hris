<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\PhilippineProvinces;
use App\Models\PhilippineCities;
use App\Models\PhilippineBarangays;
use App\Models\PhilippineRegions;
use App\Models\EmployeesChildren;

class Registration extends Component
{
    #Step 1
    public $first_name;
    public $middle_name;
    public $surname;
    public $suffix;
    public $sex;
    public $date_of_birth;
    public $place_of_birth;
    public $citizenship;
    public $civil_status;
    public $height;
    public $weight;
    public $blood_type;

    #Step 2
    public $gsis;
    public $pagibig;
    public $philhealth;
    public $sss;
    public $tin;
    public $agency_employee_no;

    #Step 3
    public $permanent_selectedRegion;
    public $permanent_selectedProvince;
    public $permanent_selectedCity;
    public $permanent_selectedBarangay;
    public $p_house_street;
    public $residential_selectedRegion;
    public $residential_selectedProvince;
    public $residential_selectedCity;
    public $residential_selectedBarangay;
    public $r_house_street;


    public $regions;
    public $pprovinces;
    public $rprovinces;
    public $pcities;
    public $rcities;
    public $pbarangays;
    public $rbarangays;


    public $tel_number;
    public $mobile_number;
    public $email;

    public $same_as_above = false;

    #Step 4
    public $spouse_name;
    public $spouse_birth_date;
    public $spouse_occupation;
    public $spouse_employer;
    public $have_child = false;
    public $have_spouse = false;
    public $childrens_name = [];
    public $childrens_birth_date = [];
    public $fathers_name;
    public $mothers_maiden_name;

    #Step 5
    public $educ_background;
    public $name_of_school;
    public $degree;
    public $period_start_date;
    public $period_end_date;
    public $year_graduated;
    public $password;
    public $c_password;

    #Step 6
    public $rating;
    public $exam_date;
    public $exam_loc;
    public $license;

    #Step 7
    public $inclusive_dates;
    public $position_title;
    public $department;
    public $monthly_salary;
    public $status_appointment;
    public $service;

    #Step 8
    public $voluntary_works;
    public $training_title;
    public $lad_inclusive_dates;
    public $number_of_hours;
    public $conducted_by;
    public $special_skills_and_hobbies;
    public $distinctions;
    public $membership;
    public $references;


    public $step = 1;

    public $children = [];

    public function addChild()
    {
        $this->children[] = ['name' => '', 'birth_date' => ''];
    }

    public function toStep2()
    {
        $this->validate([
            'first_name' => 'required|min:2',
            'middle_name' => 'required|min:2',
            'surname' => 'required|min:2',
            'suffix' => 'nullable',
            'sex' => 'required',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required',
            'citizenship' => 'required',
            'civil_status' => 'required',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'blood_type' => 'required|max:3',
        ]);

        $this->step++;
    }

    public function toStep3()
    {
        $this->validate([
            'gsis' => 'required',
            'pagibig' => 'required',
            'philhealth' => 'required',
            'sss' => 'required',
            'tin' => 'required',
            'agency_employee_no' => 'required',
        ]);

        $this->step++;
    }

    public function toStep4()
    {
        $this->validate([
            'permanent_selectedRegion' => 'required',
            'permanent_selectedProvince' => 'required',
            'permanent_selectedCity' => 'required',
            'permanent_selectedBarangay' => 'required',
            'p_house_street' => 'required',
            'residential_selectedRegion' => 'required',
            'residential_selectedProvince' => 'required',
            'residential_selectedCity' => 'required',
            'residential_selectedBarangay' => 'required',
            'r_house_street' => 'required',
            'mobile_number' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);

        $this->step++;
    }

    public function toStep5()
    {
        $this->validate([
            'fathers_name' => 'required',
            'mothers_maiden_name' => 'required',
        ]);

        $this->step++;
    }

    // public function toStep6()
    // {
    //     $this->validate([
    //         'educ_background' => 'required',
    //         'name_of_school' => 'required',
    //         'degree' => 'required',
    //         'period_of_attendance' => 'required',
    //         'year_graduated' => 'required',
    //     ]);

    //     $this->step++;
    // }

    // public function toStep7()
    // {
    //     $this->validate([
    //         'rating' => 'required|max:100|numeric',
    //         'exam_date' => 'required|date',
    //         'exam_loc' => 'required',
    //         'license' => 'required',
    //     ]);

    //     $this->step++;
    // }

    // public function toStep8()
    // {
    //     $this->validate([
    //         'inclusive_dates' => 'required|date',
    //         'position_title' => 'required',
    //         'department' => 'required',
    //         'monthly_salary' => 'required|numeric',
    //         'status_appointment' => 'required',
    //         'service' => 'required',
    //     ]);

    //     $this->step++;
    // }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate([
            'educ_background' => 'required',
            'name_of_school' => 'required',
            'degree' => 'required',
            'period_start_date' => 'required',
            'period_end_date' => 'required',
            'year_graduated' => 'required|numeric',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if (!$this->isPasswordComplex($this->password)) {
            $this->addError('password', 'The password must contain at least one uppercase letter, one number, and one special character.');
            return;
        }

        $user = User::create([
            'name' => $this->first_name . " " . $this->middle_name . " " . $this->surname,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $user->userData()->create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'surname' => $this->surname,
            'suffix' => $this->suffix,
            'sex' => $this->sex,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'citizenship' => $this->citizenship,
            'civil_status' => $this->civil_status,
            'height' => $this->height,
            'weight' => $this->weight,
            'blood_type' => $this->blood_type,
            'gsis' => $this->gsis,
            'pagibig' => $this->pagibig,
            'philhealth' => $this->philhealth,
            'sss' => $this->sss,
            'tin' => $this->tin,
            'agency_employee_no' => $this->agency_employee_no,
            'permanent_selectedRegion' => $this->permanent_selectedRegion,
            'permanent_selectedProvince' => $this->permanent_selectedProvince,
            'permanent_selectedCity' => $this->permanent_selectedCity,
            'permanent_selectedBarangay' => $this->permanent_selectedBarangay,
            'p_house_street' => $this->p_house_street,
            'residential_selectedRegion' => $this->residential_selectedRegion,
            'residential_selectedProvince' => $this->residential_selectedProvince,
            'residential_selectedCity' => $this->residential_selectedCity,
            'residential_selectedBarangay' => $this->residential_selectedBarangay,
            'r_house_street' => $this->r_house_street,
            'tel_number' => $this->tel_number,
            'mobile_number' => $this->mobile_number,
            'spouse_name' => $this->spouse_name,
            'spouse_birth_date' => $this->spouse_birth_date,
            'spouse_occupation' => $this->spouse_occupation,
            'spouse_employer' => $this->spouse_employer,
            'fathers_name' => $this->fathers_name,
            'mothers_maiden_name' => $this->mothers_maiden_name,
            'educ_background' => $this->educ_background,
            'name_of_school' => $this->name_of_school,
            'degree' => $this->degree,
            'period_start_date' => $this->period_start_date,
            'period_end_date' => $this->period_end_date,
            'year_graduated' => $this->year_graduated,
        ]);

        foreach ($this->children as $child) {
            EmployeesChildren::create([
                'user_id' => $user->id,
                'childs_name' => $child['name'],
                'childs_birth_date' => $child['birth_date'],
            ]);
        }

        session()->flash('message', 'Registration successful!');
        return redirect()->route('login');
    }

    public function mount(){
        $this->getProvicesAndCities();
    }

    public function render()
    {
        if ($this->permanent_selectedRegion != null) {
            $regionCode = PhilippineRegions::where('region_description', $this->permanent_selectedRegion)
                            ->select('region_code')->first();
            $regionCode = $regionCode->getAttributes();
            $this->pprovinces = PhilippineProvinces::where('region_code', $regionCode['region_code'])->get();
        }

        if ($this->residential_selectedRegion != null) {
            $regionCode = PhilippineRegions::where('region_description', $this->residential_selectedRegion)
                            ->select('region_code')->first();
            $regionCode = $regionCode->getAttributes();
            $this->rprovinces = PhilippineProvinces::where('region_code', $regionCode['region_code'])->get();
        }

        if ($this->permanent_selectedProvince != null) {
            $provinceCode = PhilippineProvinces::where('province_description', $this->permanent_selectedProvince)
                            ->select('province_code')->first();
            $provinceCode = $provinceCode->getAttributes();
            $this->pcities = PhilippineCities::where('province_code', $provinceCode['province_code'])->get();
        }

        if ($this->residential_selectedProvince != null) {
            $provinceCode = PhilippineProvinces::where('province_description', $this->residential_selectedProvince)
                            ->select('province_code')->first();
            $provinceCode = $provinceCode->getAttributes();
            $this->rcities = PhilippineCities::where('province_code', $provinceCode['province_code'])->get();
        }

        if ($this->permanent_selectedCity != null) {
            $cityCode = PhilippineCities::where('city_municipality_description', $this->permanent_selectedCity)
                            ->select('city_municipality_code')->first();
            $cityCode = $cityCode->getAttributes();
            $this->pbarangays = PhilippineBarangays::where('city_municipality_code', $cityCode['city_municipality_code'])->get();
        }

        if ($this->residential_selectedCity != null) {
            $cityCode = PhilippineCities::where('city_municipality_description', $this->residential_selectedCity)
                            ->select('city_municipality_code')->first();
            $cityCode = $cityCode->getAttributes();
            $this->rbarangays = PhilippineBarangays::where('city_municipality_code', $cityCode['city_municipality_code'])->get();
        }

        return view('livewire.registration',[
            'pprovinces' => $this->pprovinces,
            'rprovinces' => $this->rprovinces,
            'pcities' => $this->pcities,
            'rcities' => $this->rcities,
            'pbarangays' => $this->pbarangays,
            'rbarangays' => $this->rbarangays,
        ]);
    }

    public function getProvicesAndCities(){
        $this->regions = PhilippineRegions::all();
        $this->pprovinces = collect();
        $this->rprovinces = collect();
        $this->pcities = collect();
        $this->rcities = collect();
        $this->pbarangays = collect();
        $this->rbarangays = collect();
    }

    public function updatedSameAsAbove($value)
    {
        if ($value) {
            $this->residential_selectedRegion = $this->permanent_selectedRegion;
            $this->residential_selectedProvince = $this->permanent_selectedProvince;
            $this->residential_selectedCity = $this->permanent_selectedCity;
            $this->residential_selectedBarangay = $this->permanent_selectedBarangay;
            $this->r_house_street = $this->p_house_street;
        } else {
            $this->residential_selectedRegion = null;
            $this->residential_selectedProvince = null;
            $this->residential_selectedCity = null;
            $this->residential_selectedBarangay = null;
            $this->r_house_street = null;
        }
    }

    protected $messages = [
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 8 characters long.',
        'c_password.required' => 'The password confirmation field is required.',
        'c_password.same' => 'The password confirmation does not match the password.',
    ];

    private function isPasswordComplex($password){
        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsNumber = preg_match('/\d/', $password);
        $containsSpecialChar = preg_match('/[^A-Za-z0-9]/', $password); // Changed regex to include special characters
        return $containsUppercase && $containsNumber && $containsSpecialChar;
    }
}
