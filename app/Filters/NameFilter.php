<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class NameFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            return $builder->where('title', 'like' , '%'.$value.'%');
        }
    }
}