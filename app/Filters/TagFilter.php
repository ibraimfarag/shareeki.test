<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class TagFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            return $builder->withAnyTags($value);
        }
    }
}