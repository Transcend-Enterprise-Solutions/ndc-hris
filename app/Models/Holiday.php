<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Holiday extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'holiday_date',
        'description',
        'type',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}
