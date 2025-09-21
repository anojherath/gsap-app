<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'nic',
        'mobile_number',
        'password',
        'user_type_id',
        'image_url',
        'company_name',
        'reg_number',
        'address',
        'creation_date',
        'disabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the user type (Admin, Farmer, Harvest Buyer, etc.).
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    /**
     * Fields that belong to this user (farmer).
     */
    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Seed orders sent by or to this user.
     */
    public function seedOrders()
    {
        return $this->hasMany(SeedOrder::class, 'user_id');
    }

    /**
     * Fertilizer orders received by this user.
     */
    public function fertilizerOrders()
    {
        return $this->hasMany(FertilizerOrder::class, 'user_id');
    }

    /**
     * Agro chemical orders received by this user.
     */
    public function agroChemicalOrders()
    {
        return $this->hasMany(AgroChemicalOrder::class, 'user_id');
    }

    /**
     * Harvest orders where this user is the buyer.
     */
    public function receivedHarvestOrders()
    {
        return $this->hasMany(Harvest::class, 'buyer_id');
    }

    /**
     * Harvests created by this user (farmer).
     */
    public function createdHarvests()
    {
        return $this->hasMany(Harvest::class, 'user_id');
    }

    /**
     * Notifications received by this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
