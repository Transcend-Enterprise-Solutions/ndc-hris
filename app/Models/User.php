<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'active_status',
        'emp_code',
        'position_id',
        'office_division_id',
        'unit_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'name',
        'email',
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    public function userData()
    {
        return $this->hasOne(UserData::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function eligibility()
    {
        return $this->hasMany(Eligibility::class)->orderBy('date', 'DESC');
    }

    public function workExperience()
    {
        return $this->hasMany(WorkExperience::class)->orderBy('end_date', 'DESC');
    }

    public function employeesChildren()
    {
        return $this->hasMany(EmployeesChildren::class)->orderBy('childs_birth_date', 'ASC');
    }

    public function employeesSpouse()
    {
        return $this->hasOne(EmployeesSpouse::class);
    }

    public function employeesFather()
    {
        return $this->hasOne(EmployeesFather::class);
    }

    public function employeesMother()
    {
        return $this->hasOne(EmployeesMother::class);
    }

    public function employeesEducation()
    {
        return $this->hasMany(EmployeesEducation::class)->orderBy('level_code', 'ASC');
    }

    public function voluntaryWorks()
    {
        return $this->hasMany(VoluntaryWorks::class)->orderBy('end_date', 'DESC');
    }

    public function learningAndDevelopment()
    {
        return $this->hasMany(LearningAndDevelopment::class)->orderBy('end_date', 'DESC');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class);
    }

    public function hobbies()
    {
        return $this->hasMany(Hobbies::class);
    }

    public function nonAcadDistinctions()
    {
        return $this->hasMany(NonAcadDistinctions::class)->orderBy('date_received', 'DESC');
    }

    public function assOrgMembership()
    {
        return $this->hasMany(AssOrgMemberships::class);
    }

    public function charReferences()
    {
        return $this->hasMany(CharReferences::class);
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function docRequests()
    {
        return $this->hasMany(DocRequest::class);
    }

    public function userSchedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function leaveApplication()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function dtrSchedules()
    {
        return $this->hasMany(DTRSchedule::class, 'emp_code', 'emp_code');
    }

    public function plantillaPayslip()
    {
        return $this->hasMany(PlantillaPayslip::class);
    }

    public function cosRegPayslip()
    {
        return $this->hasMany(CosRegPayslip::class);
    }

    public function cosSkPayslip()
    {
        return $this->hasMany(CosSkPayslip::class);
    }

    public function payrolls()
    {
        return $this->hasOne(Payrolls::class);
    }

    public function employeesDtr()
    {
        return $this->hasMany(EmployeesDtr::class);
    }

    public function leaveCredits()
    {
        return $this->hasOne(LeaveCredits::class);
    }

    public function signatories()
    {
        return $this->hasMany(Signatories::class);
    }
    public function notifications()
    {
        // Assuming notifications are related to user via `user_id`
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function cosRegPayrolls(){
        return $this->hasOne(CosRegPayrolls::class);
    }

    public function cosSkPayrolls(){
        return $this->hasOne(CosSkPayrolls::class);
    }

    public function pdsC4Answers(){
        return $this->hasMany(PdsC4Answers::class);
    }

    public function pdsGovIssuedId(){
        return $this->hasOne(PdsGovIssuedId::class);
    }

    public function pdsPhoto(){
        return $this->hasOne(PdsPhoto::class);
    }

    public function monetizationRequest()
    {
        return $this->hasMany(MonetizationRequest::class);
    }

    public function leaveApprovals()
    {
        return $this->hasMany(LeaveApprovals::class, 'application_id');
    }

    public function payrollsLeaveCreditsDeduction()
    {
        return $this->hasMany(PayrollsLeaveCreditsDeduction::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('positions.position', 'like', $term)
                ->orWhere('users.emp_code', 'like', $term)
                ->orWhere('users.name', 'like', $term)
                ->orWhere('office_divisions.office_division', 'like', $term);
        });
    }

    public function adminAccount(){
        return $this->hasOne(User::class, 'name', 'name')->where('user_role', '!=', 'emp');
    }

}
