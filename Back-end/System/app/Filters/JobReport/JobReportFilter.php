<?php

namespace App\Filters\JobReport;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\JobFilter;
use App\Filters\CommonFilter\UserFilter;

class JobReportFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
        'job_id' => JobFilter::class,
        'user_id' => UserFilter::class,
    ];
}
