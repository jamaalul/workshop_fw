<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegDistrict extends Model
{
    protected $table = 'reg_districts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function regency(): BelongsTo
    {
        return $this->belongsTo(RegRegency::class, 'regency_id', 'id');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(RegVillage::class, 'district_id', 'id');
    }
}
