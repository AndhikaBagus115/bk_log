<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function logs()
    {
        return $this->hasMany(BKLog::class);
    }
}
