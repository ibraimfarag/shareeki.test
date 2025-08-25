<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseFilter
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, array $filters)
    {
        foreach($this->getFilters($filters) as $key => $filter){
            if(!$filter instanceof Filter){
                continue;
            }
            $filter->apply($builder, $this->request->get($key));
        }
        return $builder;
    }

    public function getFilters(array $filters)
    {
        // in case of taking eloquent instances only
        //return array_only($filters, array_keys($this->request->all()));
        
        return $filters;
    }
}