<?php

namespace App\Filters\Job;

class MaxSalaryFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('max_salary', $value);
    }
}
