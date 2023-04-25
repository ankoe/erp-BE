<?php

namespace App\Traits\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionException;

abstract class Filter
{
    /**
     * @var array
     */
    protected $criteria = [];

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Filter constructor.
     *
     * @param Request $criteria
     */
    public function __construct(Request $criteria)
    {
        $this->criteria = $criteria->all();
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    protected function getFilterMethods(): array
    {
        $class = new ReflectionClass(static::class);

        return collect($class->getMethods())->filter(function ($method) use ($class) {
            return $method->class === $class->getName();
        })->map(function ($method) {
            return $method->name;
        })->filter()->all();
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    protected function getFilters(): array
    {
        return array_filter(Arr::only($this->criteria, $this->getFilterMethods()), function ($value) {
            return isset($value);
        });
    }

    /**
     * @param Builder $builder
     * @return Builder
     * @throws ReflectionException
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
            }
        }
        return $this->builder;
    }
}