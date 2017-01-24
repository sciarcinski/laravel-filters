<?php

namespace Sciarcinski\LaravelFilters;

use Sciarcinski\LaravelFilters\Filter;

trait Filterable
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Filter $filter
     * @param string $input
     * @param array $use_only
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltered($query, $filter, $input = 'filter', array $use_only = ['*'])
    {
        if (!$filter instanceof Filter) {
            $filter = app()->make($filter);
        }
        
        return $filter->apply($query, $input, $use_only);
    }
}
