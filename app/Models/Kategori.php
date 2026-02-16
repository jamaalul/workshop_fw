<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'nama_kategori'
    ];

    public function buku() {
        return $this->hasMany(Buku::class);
    }
}
