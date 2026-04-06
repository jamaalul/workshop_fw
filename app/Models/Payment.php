<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    
    protected $fillable = [
        'idpesanan', 'transaction_id', 'payment_type', 'gross_amount', 
        'payment_status', 'fraud_status', 'va_number', 'bank', 
        'bill_key', 'biller_code', 'qr_code_url', 'expiry_time', 
        'paid_at', 'midtrans_response'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'expiry_time' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }
}
