<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budget';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'jumlah',
        'bulan',
        'tahun',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}