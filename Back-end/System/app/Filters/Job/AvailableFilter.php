<?php

namespace App\Filters\Job;

class AvailableFilter
{
    public function filter($builder, $value)
    {
        if ($value == 1) {
            return $builder->where('deadline', '>=', now())
                ->orWhere('status', 'Đang tuyển');
        }

        return $builder->where('deadline', '<', now())->orWhere('status', 'Ngừng tuyển');
    }
}
