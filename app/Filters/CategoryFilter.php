<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){

            $params =  explode('&', $value);
            $categoryIds = [];
            foreach ($params as $param) { $name_value = explode('=', $param); array_push($categoryIds, $name_value[1]); }

           
        
            return $builder->whereIn('category_id' , $categoryIds);
        }
    }
}