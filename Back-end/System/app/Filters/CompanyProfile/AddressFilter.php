<?php

namespace App\Filters\CompanyProfile;

class AddressFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('address', 'like', '%' . $value . '%');
    }
}
