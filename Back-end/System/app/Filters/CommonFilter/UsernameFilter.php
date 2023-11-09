<?php

namespace App\Filters\CommonFilter;

class UsernameFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('username', 'like', '%' . $value . '%');
    }
}
