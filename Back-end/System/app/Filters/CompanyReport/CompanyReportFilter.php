<?php

namespace App\Filters\CompanyReport;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UserFilter;

class CompanyReportFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
        'user_id' => UserFilter::class,
    ];
}
