<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegVillage extends Model
{
    protected $table = 'reg_villages';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    public function district(): BelongsTo
    {
        return $this->belongsTo(RegDistrict::class, 'district_id', 'id');
    }
}
