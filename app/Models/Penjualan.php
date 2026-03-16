<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualans';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';

    protected $fillable = [
        'timestamp',
        'total',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id');
    }
}
