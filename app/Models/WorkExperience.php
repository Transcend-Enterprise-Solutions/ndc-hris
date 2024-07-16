<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $table = 'work_experience';

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'position',
        'department',
        'monthly_salary',
        'status_of_appointment',
        'gov_service',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
