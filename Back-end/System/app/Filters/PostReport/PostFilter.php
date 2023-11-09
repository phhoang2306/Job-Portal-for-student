<?php

namespace App\Filters\PostReport;

class PostFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('post_id', $value);
    }
}
