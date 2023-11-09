<?php

namespace App\Filters\Job;

class CategoryFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('categories', function ($query) use ($value) {
            $query->where('description', 'like', '%'.$value.'%');
        });
    }
}
