<?php

namespace App\Http\Controllers;
use Spatie\Tags\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $theTags = Tag::all(["id", "name"]);
        $tags = [];
        foreach($theTags as $tag){
            array_push($tags, ["id" => $tag->id, "text" => $tag->name]);
        }
        return $tags;
    }
}
