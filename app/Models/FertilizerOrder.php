<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FertilizerOrder extends Model
{
    use HasFactory;

    protected $table = 'fertiliser_order'; // Note spelling: match your DB table name exactly

    protected $fillable = [
        'user_id',              // the farmer
        'fertilizer_provider_id', // the sender
        'fertilizer_id',
        'qty',
        'type',
        'creation_date',
        'farmer_confirmed',
    ];

    public $timestamps = false;

    protected $casts = [
        'creation_date' => 'datetime',
        'farmer_confirmed' => 'boolean',
    ];

    // The receiving farmer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The fertilizer provider (sender of the order)
    public function fertilizerProvider()
    {
        return $this->belongsTo(User::class, 'fertilizer_provider_id');
    }

    // The fertilizer item
    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class, 'fertilizer_id');
    }

    public function getFarmerConfirmedAttribute($value)
    {
        return is_null($value) ? null : (bool) $value;
    }
}
