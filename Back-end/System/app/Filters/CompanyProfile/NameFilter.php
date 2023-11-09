<?php

namespace App\Filters\CompanyProfile;

class NameFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('name', 'like', '%' . $value . '%');
    }
}
