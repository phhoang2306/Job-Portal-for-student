<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'categories';

    protected $fillable = [
        'description',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_category', 'category_id', 'job_id');
    }

    public function job_category(): HasMany
    {
        return $this->hasMany(JobCategory::class, 'category_id', 'id');
    }
}
