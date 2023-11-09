<?php

namespace App\Filters\Job;

class MinYOEFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('min_yoe', $value);
    }
}
