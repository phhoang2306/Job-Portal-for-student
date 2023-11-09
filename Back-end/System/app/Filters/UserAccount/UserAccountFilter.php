<?php

namespace App\Filters\UserAccount;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UsernameFilter;

class UserAccountFilter extends AbstractFilter
{
    protected $filters = [
        'username' => UsernameFilter::class,
    ];
}
