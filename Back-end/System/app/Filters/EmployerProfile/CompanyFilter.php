<?php

namespace App\Filters\EmployerProfile;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('company_id', $value);
    }
}
