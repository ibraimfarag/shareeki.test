<?php 

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class MainCategoryFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){

            $params =  explode('&', $value);
            $categoryIds = [];
            foreach ($params as $param) { $name_value = explode('=', $param); array_push($categoryIds, $name_value[1]); }
            
            $category = Category::whereIn('id', $categoryIds)->with('subcategories')->get();
            
            $ids = [];

            for ($i=0; $i < count($category); $i++) { 
                $theIds = $category[$i]->subcategories->map(function ($s) {
                    return collect($s->toArray())
                        ->only(['id'])
                        ->all();
                });

                array_push($ids, $theIds);
            }
            
            $allIds = [];

            foreach ($ids as $id) {
                foreach ($id as $theId) {
                    array_push($allIds, $theId['id']);
                }
            }
            
            return $builder->whereIn('category_id' , $allIds);
        }
    }
}