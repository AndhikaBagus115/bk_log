<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BKLog extends Model
{
    protected $table = 'bk_logs';

    protected $fillable = [
        'nomor_absen',
        'nama_murid',
        'kelas',
        'catatan',
        'poin',
        'tanggal_input',
        'minggu_ke',
        'bulan'
    ];
}

