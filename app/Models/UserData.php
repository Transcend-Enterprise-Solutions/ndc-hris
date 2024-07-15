<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserData extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_data';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'surname',
        'first_name',
        'middle_name',
        'name_extension',
        'date_of_birth',
        'place_of_birth',
        'civil_status',
        'citizenship',
        'age',
        'tel_number',
        'mobile_number',
        'email',
        'blood_type',
        'sex',
        'height',
        'weight',
        'gsis',
        'pagibig',
        'philhealth',
        'sss',
        'tin',
        'agency_employee_no',
        'permanent_selectedRegion',
        'permanent_selectedProvince',
        'permanent_selectedCity',
        'permanent_selectedBarangay',
        'p_house_street',
        'residential_selectedRegion',
        'residential_selectedProvince',
        'residential_selectedCity',
        'residential_selectedBarangay',
        'r_house_street',
        'educ_background',
        'name_of_school',
        'degree',
        'period_start_date',
        'period_end_date',
        'year_graduated',
        // 'spouse_name',
        // 'spouse_birth_date',
        // 'spouse_occupation',
        // 'spouse_employer',
        // 'fathers_name',
        // 'mothers_maiden_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
