<?php

namespace App\Filters\AdminAccount;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UsernameFilter;

class AdminAccountFilter extends AbstractFilter
{
    protected $filters = [
        'username' => UsernameFilter::class,
    ];
}
