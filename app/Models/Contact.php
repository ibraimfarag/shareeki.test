<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];
    
    
    public function getImgPathAttribute()
    {
        return $this->image != null ? asset('storage/main/contacts/'. $this->image) : 'https://i.ibb.co/G9JQYCP/download.jpg';
    }
}
