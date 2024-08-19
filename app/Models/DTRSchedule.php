<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class DTRSchedule extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'dtrschedules';

    protected $fillable = [
        'emp_code',
        'wfh_days',
        'default_start_time',
        'default_end_time',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'emp_code', 'emp_code');
    }

    /**
     * Customize the description of the audit log.
     *
     * @return string
     */
    public function getAuditDescriptionAttribute()
    {
        $userName = $this->user->name ?? 'System';
        $action = ucfirst($this->auditable_type) . ' ' . $this->event;
        $id = $this->auditable_id;

        return "User $userName $action a new schedule (ID: $id).";
    }
}
