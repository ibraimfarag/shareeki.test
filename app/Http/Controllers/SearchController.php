<?php

namespace App\Http\Controllers;

use App\Filters\AreaFilter;
use App\Filters\CategoryFilter;
use App\Filters\MainCategoryFilter;
use App\Filters\NameFilter;
use App\Filters\PartnersNoFilter;
use App\Filters\PriceFilter;
use App\Filters\SortFilter;
use App\Filters\TagFilter;
use App\Filters\WeeksHoursFilter;
use App\Models\Post;

class SearchController extends Controller
{
    public function getFilters()
    {
        //dd(request()->all());
        $posts = Post::filter($this->filters())
            ->with('category:id,name,slug,image')
            ->latest()
            ->paginate(20);
        /*foreach ($posts as $post) {
            $post->areaName = Post::getMainArea($post->area_id);
            $post->views = views($post)->unique()->count();
        }*/

        //dd(count($posts));

        $html = view('main.posts.includes.posts', compact('posts'))->render();

        return response()->json(compact('html'));

        //return view('main.posts.index', compact('posts'));
    }

    protected function filters()
    {
        return [
            'name' => new NameFilter,
            'area_id' => new AreaFilter,
            'main_category' => new MainCategoryFilter,
            'category_id' => new CategoryFilter,
            'partners_no' => new PartnersNoFilter,
            'price' => new PriceFilter,
            'sort' => new SortFilter,
            'weeks_hours' => new WeeksHoursFilter,
            'the_tags' => new TagFilter,
        ];
    }    
}
