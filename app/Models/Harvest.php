<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    protected $table = 'harvest';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'paddy_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paddy()
    {
        return $this->belongsTo(Paddy::class, 'paddy_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
