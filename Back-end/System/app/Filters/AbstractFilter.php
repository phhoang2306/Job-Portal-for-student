<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

abstract class AbstractFilter
{
    /**
     * @var Request
     */
    protected Request $request;

    protected $filters = [];

    /**
     * AbstractFilter constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        foreach ($this->filters() as $name => $value) {
            $this->resolveFilterValue($name)->filter($builder, $value);
        }

        return $builder;
    }

    /**
     * @return array
     */
    protected function filters(): array
    {
        return array_filter($this->request->only(array_keys($this->filters)));
    }

    protected function resolveFilterValue($filter)
    {
        return new $this->filters[$filter];
    }

}
