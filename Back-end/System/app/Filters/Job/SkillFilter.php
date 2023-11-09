<?php

namespace App\Filters\Job;

class SkillFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('skills', function ($query) use ($value) {
            $query->where('skill', 'like', '%'.$value.'%');
        });
    }
}
