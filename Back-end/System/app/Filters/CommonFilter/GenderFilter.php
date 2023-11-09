<?php

namespace App\Filters\CommonFilter;

class GenderFilter
{
    public function filter($builder, $value)
    {
        return match ($value) {
            0 => $builder->where('gender', 'Không yêu cầu'),
            1 => $builder->where('gender', 'Nam'),
            2 => $builder->where('gender', 'Nữ'),
            default => $builder,
        };
    }
}
