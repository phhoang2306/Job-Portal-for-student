<?php

namespace App\Filters\Job;

class TypeFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('type', $value);
    }
}
