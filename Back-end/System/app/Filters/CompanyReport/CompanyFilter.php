<?php

namespace App\Filters\CompanyReport;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->where('company_id', $value);
    }
}
