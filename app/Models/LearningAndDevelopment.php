<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningAndDevelopment extends Model
{
    use HasFactory;

    protected $table = 'learning_and_development';

    protected $fillable = [
        'user_id',
        'title',
        'start_date',
        'end_date',
        'no_of_hours',
        'conducted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
