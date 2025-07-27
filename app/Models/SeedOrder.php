<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeedOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paddy_id',
        'qty',
        'creation_date',
        'farmer_confirmed',
    ];

    // 👇 These are the relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paddy()
    {
        return $this->belongsTo(Paddy::class);
    }
}
