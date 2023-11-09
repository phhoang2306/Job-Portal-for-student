<?php

namespace App\Filters\Job;

class TitleFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('title', 'like', '%' . $value . '%');
    }
}
