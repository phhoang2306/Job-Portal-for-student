<?php

namespace App\Filters\CompanyVerification;

use App\Filters\AbstractFilter;

class CompanyVerificationFilter extends AbstractFilter
{
    protected $filters = [
        'company_id' => CompanyFilter::class,
        'status' => StatusFilter::class,
    ];
}
