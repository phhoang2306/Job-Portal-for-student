<?php

namespace App\Filters\CommonFilter;

class JobFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('job_id', $value);
    }
}
