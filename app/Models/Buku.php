<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'kode',
        'judul',
        'pengarang',
        'kategori_id'
    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }
}
