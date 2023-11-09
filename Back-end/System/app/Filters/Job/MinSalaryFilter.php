<?php

namespace App\Filters\Job;

class MinSalaryFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('min_salary', '>=', $value);
    }
}
