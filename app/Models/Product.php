<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $guarded = ['id'];
    protected $fillable = [
        'kd_barang',
        'nama_barang',
        'jenis_barang',
        'harga_jual',
        'stok',
        'tgl_expired',
        'foto_barang',
    ];
}
