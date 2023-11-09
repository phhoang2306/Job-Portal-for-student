<?php

namespace App\Filters\Job;

class LocationFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('location', 'like', '%' . $value . '%');
    }
}
