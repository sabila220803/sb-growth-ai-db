<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'date_of_birth',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
}