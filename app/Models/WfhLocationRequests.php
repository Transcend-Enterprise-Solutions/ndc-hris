<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WfhLocationRequests extends Model
{
    use HasFactory;

    protected $table = 'wfh_location_requests';

    protected $fillable = [
        'user_id',
        'message',
        'attachment',
        'curr_lat',
        'curr_lng',
        'status',
        'approver',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
