<?php

namespace App\Filters\PostComment;

use App\Filters\AbstractFilter;
use App\Filters\CommonFilter\UserFilter;

class PostCommentFilter extends AbstractFilter
{
    protected $filters = [
        'post_id' => PostFilter::class,
        'user_id' => UserFilter::class,
    ];
}
