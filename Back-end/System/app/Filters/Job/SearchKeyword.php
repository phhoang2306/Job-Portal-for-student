<?php

namespace App\Filters\Job;

class SearchKeyword
{
    public function filter($builder, $value)
    {
        return $builder->where('title', 'like', '%'.$value.'%')
            ->orWhere('description', 'like', '%'.$value.'%')
            ->orWhere('location', 'like', '%'.$value.'%')
            ->orWhere('requirement', 'like', '%'.$value.'%')
            ->orWhere('type', 'like', '%'.$value.'%')
            ->orWhere('position', 'like', '%'.$value.'%')
            ->orWhereHas('skills', function ($query) use ($value) {
                $query->where('skill', 'like', '%'.$value.'%');
            })
            ->orWhereHas('categories', function ($query) use ($value) {
                $query->where('description', 'like', '%'.$value.'%');
            });
    }
}
