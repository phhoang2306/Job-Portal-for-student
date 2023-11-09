<?php

namespace App\Filters\EmployerAccount;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('profile', function ($query) use ($value) {
            $query->where('company_id', $value);
        });
    }
}
