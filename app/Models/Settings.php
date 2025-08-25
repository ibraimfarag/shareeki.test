<?php

namespace App\Models;

use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $guarded = [];

    //Get updated_at Attribute In Hijri
    public function getLastUpdateHijriAttribute()
    {
        return Hijri::DateMediumFormat($this->updated_at);
    }

    //Get updated_at In Arabic With Format ('d M Y')
    public function getLastUpdateAttribute()
    {
        return Carbon::parse($this->updated_at)->translatedFormat('d M Y');
    }
}
