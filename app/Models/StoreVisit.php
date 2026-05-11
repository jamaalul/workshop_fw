<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'sales_latitude',
        'sales_longitude',
        'sales_accuracy',
        'distance_meters',
        'status',
        'visited_at',
    ];

    protected $casts = [
        'sales_latitude' => 'float',
        'sales_longitude' => 'float',
        'sales_accuracy' => 'float',
        'distance_meters' => 'float',
        'visited_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
