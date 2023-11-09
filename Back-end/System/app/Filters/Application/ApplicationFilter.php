<?php

namespace App\Filters\Application;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\JobFilter;
use App\Filters\CommonFilter\UserFilter;

class ApplicationFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
        'job_id' => JobFilter::class,
        'user_id' => UserFilter::class,
        'status' => StatusFilter::class,
    ];
}
