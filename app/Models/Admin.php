<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Admin extends AuthenticatableUser implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id', 
        'payroll_id', 
        'department',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeSearch($query, $term){
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where('admin.department', 'like', $term)
                ->orWhere('payrolls.employee_number', 'like', $term)
                ->orWhere('payrolls.position', 'like', $term)
                ->orWhere('payrolls.office_division', 'like', $term)
                ->orWhere('payrolls.department', 'like', $term)
                ->orWhere('users.name', 'like', $term)
                ->orWhere('users.email', 'like', $term);
        });
    }


}
