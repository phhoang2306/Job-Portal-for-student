<?php

namespace App\Filters\Job;

class TopFilter
{
    public function filter($builder, $value)
    {
        return $builder->withCount('applications')->orderBy('applications_count', 'desc')->take($value);
    }
}
