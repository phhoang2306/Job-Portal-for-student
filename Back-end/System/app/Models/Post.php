<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $collection = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'cv_id',
        'upvote',
        'downvote',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    public function cv(): HasOne
    {
        return $this->hasOne(CV::class, 'id', 'cv_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'id');
    }

    public function post_reports(): HasMany
    {
        return $this->hasMany(PostReport::class, 'post_id', 'id');
    }

    public function post_comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
    }
}
