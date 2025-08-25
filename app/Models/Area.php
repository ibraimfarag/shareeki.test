<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public static function getMainAreas()
    {
        return self::whereParentId(1)->orderBy('position')->get();
    }

    public static function getMainArea($id)
    {
        return self::whereId($id)->firstOrFail();
    }

    public static function getChildrenAreas($id)
    {
        return self::whereParentId($id)->get();
    }
}
