<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
    'first_name', 'last_name', 'nic', 'mobile_number', 'password',
    'user_type_id', 'image_url', 'company_name', 'reg_number',
    'address', 'creation_date', 'disabled'
    ];

    protected $hidden = [
    'password'
    ];

}
