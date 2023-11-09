<?php

namespace App\Filters\SavedJob;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\JobFilter;
use App\Filters\CommonFilter\UserFilter;

class SavedJobFilter extends AbstractFilter
{
    protected $filters = [
        'job_id' => JobFilter::class,
        'user_id' => UserFilter::class,
        'company_id' => CompanyFilter::class,
    ];
}
