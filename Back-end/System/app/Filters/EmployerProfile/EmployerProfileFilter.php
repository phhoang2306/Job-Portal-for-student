<?php

namespace App\Filters\EmployerProfile;

use App\Filters\AbstractFilter;

class EmployerProfileFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
    ];
}
