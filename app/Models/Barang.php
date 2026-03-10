<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    //
    protected $fillable = ['kategori_id', 'nama', 'deskripsi', 'harga', 'stok', 'tanggal_pembelian'];

    protected $casts = ['harga' => 'decimal:2', 'stok' => 'integer'];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
