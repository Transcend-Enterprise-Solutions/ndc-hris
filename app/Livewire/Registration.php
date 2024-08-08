<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\PhilippineProvinces;
use App\Models\PhilippineCities;
use App\Models\PhilippineBarangays;
use App\Models\PhilippineRegions;
// use App\Models\EmployeesChildren;

class Registration extends Component
{
    public $user_role = 'emp';
    public $active_status = '';
    public $emp_code;

    #Step 1
    public $first_name;
    public $middle_name;
    public $surname;
    public $name_extension;
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
    public $permanent_selectedZipcode;
    public $residential_selectedZipcode;
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
    public $password;
    public $c_password;
    public $step = 1;

    public function toStep2()
    {
        $this->validate([
            'first_name' => 'required|min:2',
            'middle_name' => 'nullable',
            'surname' => 'required|min:2',
            'name_extension' => 'nullable',
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

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate([
            'permanent_selectedZipcode' => 'required',
            'permanent_selectedProvince' => 'required',
            'permanent_selectedCity' => 'required',
            'permanent_selectedBarangay' => 'required',
            'p_house_street' => 'required',
            'residential_selectedZipcode' => 'required',
            'residential_selectedProvince' => 'required',
            'residential_selectedCity' => 'required',
            'residential_selectedBarangay' => 'required',
            'r_house_street' => 'required',
            'mobile_number' => ['required', 'regex:/^\+639\d{9}$|^\d{11}$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
            'emp_code' => 'required',
        ]);

        if (!$this->isPasswordComplex($this->password)) {
            $this->addError('password', 'The password must contain at least one uppercase letter, one number, and one special character.');
            return;
        }

        $currentYear = now()->year;
        $userCount = User::whereYear('created_at', $currentYear)->count();
        //$empCode = $currentYear . ($userCount + 1);

        $user = User::create([
            'name' => $this->first_name . " " . $this->middle_name . " " . $this->surname,
            'email' => $this->email,
            'password' => $this->password,
            'user_role' => 'emp',
            'active_status' => $this->active_status,
            'emp_code' => $this->emp_code,
        ]);

        $user->userData()->create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'surname' => $this->surname,
            'name_extension' => $this->name_extension,
            'sex' => $this->sex,
            'email' => $this->email,
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
            'permanent_selectedZipcode' => $this->permanent_selectedZipcode,
            'permanent_selectedProvince' => $this->permanent_selectedProvince,
            'permanent_selectedCity' => $this->permanent_selectedCity,
            'permanent_selectedBarangay' => $this->permanent_selectedBarangay,
            'p_house_street' => $this->p_house_street,
            'residential_selectedZipcode' => $this->residential_selectedZipcode,
            'residential_selectedProvince' => $this->residential_selectedProvince,
            'residential_selectedCity' => $this->residential_selectedCity,
            'residential_selectedBarangay' => $this->residential_selectedBarangay,
            'r_house_street' => $this->r_house_street,
            'tel_number' => $this->tel_number,
            'mobile_number' => $this->mobile_number,
        ]);

        session()->flash('message', 'Registration successful!');
        return redirect()->route('login');
    }

    public function mount(){
        $this->getProvicesAndCities();
    }

    public function render()
    {
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
        $this->pprovinces = PhilippineProvinces::all();
        $this->pcities = collect();
        $this->rcities = collect();
        $this->pbarangays = collect();
        $this->rbarangays = collect();
    }

    public function updatedSameAsAbove($value)
    {
        if ($value) {
            $this->residential_selectedZipcode = $this->permanent_selectedZipcode;
            $this->residential_selectedProvince = $this->permanent_selectedProvince;
            $this->residential_selectedCity = $this->permanent_selectedCity;
            $this->residential_selectedBarangay = $this->permanent_selectedBarangay;
            $this->r_house_street = $this->p_house_street;
        } else {
            $this->residential_selectedZipcode = null;
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
