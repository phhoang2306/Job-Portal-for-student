<?php

namespace App\Filters\CV;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UserFilter;

class CVFilter extends AbstractFilter
{
    protected $filters = [
        'user_id' => UserFilter::class,
    ];
}
