<?php

namespace App\Filters\Application;

class CompanyFilter
{
    public function filter($builder, $value)
    {
        return $builder->whereHas('job', function ($query) use ($value) {
            $query->whereHas('employer_profile', function ($query) use ($value) {
                $query->where('company_id', $value);
            });
        });
    }
}
