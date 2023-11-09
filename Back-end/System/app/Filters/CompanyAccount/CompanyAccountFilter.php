<?php

namespace App\Filters\CompanyAccount;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UsernameFilter;

class CompanyAccountFilter extends AbstractFilter
{
    protected $filters = [
        'username' => UsernameFilter::class,
    ];
}
