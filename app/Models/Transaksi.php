<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['tanggal_transaksi', 'jumlah_penjualan'];

    protected function casts(): array {
        return [
            'tanggal_transaksi' => 'date',
            'jumlah_penjualan' => 'integer'
        ];
    }
}