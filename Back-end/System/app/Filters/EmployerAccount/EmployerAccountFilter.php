<?php

namespace App\Filters\EmployerAccount;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UsernameFilter;

class EmployerAccountFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
        'username' => UsernameFilter::class,
    ];
}
