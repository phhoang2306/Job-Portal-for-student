<?php

namespace App\Filters\Application;

class StatusFilter
{
    public function filter($builder, $value)
    {
        return match ($value) {
            '0' => $builder->where('status', 'Đang chờ'),
            '1' => $builder->where('status', 'Đã duyệt'),
            '2' => $builder->where('status', 'Đã từ chối'),
            default => $builder,
        };
    }
}
