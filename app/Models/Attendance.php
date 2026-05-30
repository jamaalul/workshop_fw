<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'nfc_card_id',
        'tanggal',
        'waktu',
        'status',
    ];

    public function nfcCard(): BelongsTo
    {
        return $this->belongsTo(NfcCard::class);
    }
}
