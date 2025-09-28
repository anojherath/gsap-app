<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    protected $table = 'harvest'; // your existing table name

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'paddy_id',
        'fertilizer_id', // store FertilizerOrder ID
        'field_id',
        'qty',
        'buyer_id',
        'status',
        'creation_date',
    ];

    // Cast creation_date to Carbon instance
    protected $casts = [
        'creation_date' => 'datetime',
    ];

    // Relations

    // Farmer who created the harvest
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Paddy type
    public function paddy()
    {
        return $this->belongsTo(Paddy::class, 'paddy_id');
    }

    // Field
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // Harvest buyer
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Fertilizer order used in this harvest
    public function fertilizerOrder()
    {
        return $this->belongsTo(FertilizerOrder::class, 'fertilizer_id'); // points to FertilizerOrder
    }

    // Convenience accessor to directly get fertilizer type
    public function getFertilizerAttribute()
    {
        return $this->fertilizerOrder ? $this->fertilizerOrder->type : null;
    }
}
