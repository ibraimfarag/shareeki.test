<?php 

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class SortFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        if($value !== null){
            $params =  explode('&', request()->sort);
            $sorts = [];
            foreach ($params as $param) { $name_value = explode('=', $param); array_push($sorts, $name_value[1]); }       
            foreach ($params as &$param) { if($param == 'sort%5B%5D=0') { $param = 'فكرة'; }else{ $param = 'عمل قائم'; }  }
           
            return count($params) == 2 ? $builder->where('sort' , $params[0])->orWhere('sort' , $params[1]) : $builder->where('sort' , $params[0]);
        }    
    }
}