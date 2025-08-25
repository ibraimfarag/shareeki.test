<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class AreaFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            return $builder->where('area_id' , $value);
        }
    }
}