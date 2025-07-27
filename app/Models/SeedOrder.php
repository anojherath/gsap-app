<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeedOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',            // the farmer
        'seed_provider_id',   // the sender
        'paddy_id',
        'qty',
        'creation_date',
        'farmer_confirmed',
    ];

    // Automatically cast creation_date to Carbon instance
    protected $casts = [
        'creation_date' => 'datetime',
        'farmer_confirmed' => 'boolean',
    ];

    // The receiving farmer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The seed provider (sender of order)
    public function seedProvider()
    {
        return $this->belongsTo(User::class, 'seed_provider_id');
    }

    public function paddy()
    {
        return $this->belongsTo(Paddy::class);
    }

    public function getFarmerConfirmedAttribute($value)
    {
        return is_null($value) ? null : (bool) $value;
    }

}
