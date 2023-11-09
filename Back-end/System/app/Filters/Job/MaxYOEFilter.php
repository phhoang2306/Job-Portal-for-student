<?php

namespace App\Filters\Job;

class MaxYOEFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('max_yoe', $value);
    }
}
