<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\PhilippineProvinces;
use App\Models\PhilippineCities;
use App\Models\PhilippineBarangays;
use App\Models\PhilippineRegions;

class Registration extends Component
{
    #Step 1
    public $first_name;
    public $middle_name;
    public $surname;
    public $suffix;
    public $sex;
    public $date_of_birth;
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
    public $agency;

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


    public $step = 1;

    public function toStep2()
    {
        $this->validate([
            'first_name' => 'required|min:2',
            'middle_name' => 'required|min:2',
            'surname' => 'required|min:2',
            'suffix' => 'nullable',
            'sex' => 'required',
            'date_of_birth' => 'required|date|before:today',
            'citizenship' => 'required',
            'civil_status' => 'required',
            'height' => 'required',
            'weight' => 'required',
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
            'agency' => 'required',
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
        ]);

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate();

        // Save the data to the database
        User::create($this->formData);

        // Redirect or show success message
        session()->flash('message', 'Registration successful!');
        return redirect()->route('home'); // Adjust this to your route
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
}
