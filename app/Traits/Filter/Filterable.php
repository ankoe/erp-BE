<?php

namespace App\Traits\Filter;

use Illuminate\Database\Eloquent\Builder;
use ReflectionException;

trait Filterable
{
    /**
     * @param Builder $query
     * @param Filter $filter
     * @return Builder
     * @throws ReflectionException
     */
    public function scopeFilter(Builder $query, Filter $filter): Builder
    {
        return $filter->apply($query);
    }
}