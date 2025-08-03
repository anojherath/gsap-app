<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'size',
        'address',
        'creation_date'
    ];

    /**
     * The user (farmer) who owns the field.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fertilizer orders associated with the field.
     */
    public function fertilizerOrders()
    {
        return $this->hasMany(FertilizerOrder::class, 'field_id');
    }

    /**
     * Harvest orders associated with the field.
     */
    public function harvestOrders()
    {
        return $this->hasMany(HarvestOrder::class, 'field_id');
    }
}
