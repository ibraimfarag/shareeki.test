<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class PartnersNoFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            return $builder->WhereBetween('partners_no' , [0,$value]);   
        }
    }
}