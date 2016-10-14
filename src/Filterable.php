<?php

namespace Sciarcinski\LaravelFilters;

use Sciarcinski\LaravelFilters\Filter;

trait Filterable
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Filter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltered($query, $filter)
    {
        if (!$filter instanceof Filter) {
            $filter = app()->make($filter);
        }
        
        return $filter->apply($query);
    }
}
