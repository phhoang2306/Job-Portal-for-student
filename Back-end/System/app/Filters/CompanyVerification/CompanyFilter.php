<?php

namespace App\Filters\CompanyVerification;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('company_id', $value);
    }
}
