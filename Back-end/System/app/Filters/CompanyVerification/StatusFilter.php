<?php

namespace App\Filters\CompanyVerification;

class StatusFilter
{
    public function filter($builder, $value)
    {
        return match ($value) {
            '0' => $builder->where('status', 'Đang chờ'),
            '1' => $builder->where('status', 'Hợp lệ'),
            '2' => $builder->where('status', 'Không hợp lệ'),
            default => $builder,
        };
    }
}
