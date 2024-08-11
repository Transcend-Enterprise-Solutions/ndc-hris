<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'date_requested',
        'status',
        'date_completed',
        'file_path',
        'filename',
    ];

    protected $casts = [
        'date_requested' => 'datetime',
        'date_completed' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
}
