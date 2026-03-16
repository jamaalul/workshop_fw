<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegProvince extends Model
{
    protected $table = 'reg_provinces';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function regencies(): HasMany
    {
        return $this->hasMany(RegRegency::class, 'province_id', 'id');
    }
}
