<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class WeeksHoursFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            return $builder->WhereBetween('weeks_hours' , [0,$value]);
        }
    }
}