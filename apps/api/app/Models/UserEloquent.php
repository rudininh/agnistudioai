<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEloquent extends Model
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'is_verified',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function getKeyType()
    {
        return 'string';
    }

    public function incrementing()
    {
        return false;
    }
}
