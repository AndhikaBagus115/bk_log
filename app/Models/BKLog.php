<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BKLog extends Model
{
    protected $table = 'bk_logs';

    protected $fillable = [
        'client_id',
        'nomor_absen',
        'nama_murid',
        'kelas',
        'catatan',
        'poin',
        'tanggal_input',
        'minggu_ke',
        'bulan'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
