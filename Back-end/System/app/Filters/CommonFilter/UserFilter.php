<?php

namespace App\Filters\CommonFilter;

class UserFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('user_id', $value);
    }
}
