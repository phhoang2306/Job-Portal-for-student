<?php

namespace App\Filters\PostComment;

class PostFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('post_id', $value);
    }
}
