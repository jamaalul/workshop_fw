<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;
    
    protected $fillable = ['nama', 'timestamp', 'total', 'metode_bayar', 'status_bayar'];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'idpesanan', 'idpesanan');
    }

    public function isPaid()
    {
        return $this->status_bayar == 1;
    }
}
