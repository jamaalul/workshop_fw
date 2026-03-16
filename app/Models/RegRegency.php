<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegRegency extends Model
{
    protected $table = 'reg_regencies';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function province(): BelongsTo
    {
        return $this->belongsTo(RegProvince::class, 'province_id', 'id');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(RegDistrict::class, 'regency_id', 'id');
    }
}
