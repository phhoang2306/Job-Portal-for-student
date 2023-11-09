<?php

namespace App\Filters\Job;

class EmployerFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('employer_id', $value);
    }
}
