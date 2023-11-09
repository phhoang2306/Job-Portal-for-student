<?php

namespace App\Filters\CompanyProfile;

use App\Filters\AbstractFilter;

class CompanyProfileFilter extends AbstractFilter
{
    protected $filters = [
        'name' => NameFilter::class,
        'address' => AddressFilter::class
    ];
}
