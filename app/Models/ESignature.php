<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ESignature extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file_path'];
    protected $table = 'e_signatures';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
