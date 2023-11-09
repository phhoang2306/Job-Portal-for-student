<?php

namespace App\Filters\Job;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('employer_profile', function ($query) use ($value) {
            $query->where('company_id', $value);
        });
    }
}
