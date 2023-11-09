<?php

namespace App\Filters\UserHistory;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\JobFilter;
use App\Filters\CommonFilter\UserFilter;

class UserHistoryFilter extends AbstractFilter
{
    protected $filters = [
        'user_id' => UserFilter::class,
        'job_id' => JobFilter::class,
    ];
}
