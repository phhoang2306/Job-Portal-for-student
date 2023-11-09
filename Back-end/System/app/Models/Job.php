<?php

namespace App\Models;

use App\Filters\Job\JobFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'benefit',
        'requirement',
        'type',
        'location',
        'min_salary',
        'max_salary',
        'recruit_num',
        'position',
        'min_yoe',
        'max_yoe',
        'gender',
        'deadline',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    public static function filter($request, $builder): Builder
    {
        return (new JobFilter($request))->apply($builder);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(EmployerAccount::class, 'employer_id', 'id');
    }

    public function employer_profile(): BelongsTo
    {
        return $this->belongsTo(EmployerProfile::class, 'employer_id', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id', 'id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(JobReport::class, 'job_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(JobSkill::class, 'job_id', 'id');
    }

    public function user_histories(): HasMany
    {
        return $this->hasMany(UserHistory::class, 'job_id', 'id');
    }

    public function saved_jobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'job_id', 'id');
    }

    public function job_category(): HasMany
    {
        return $this->hasMany(JobCategory::class, 'job_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'job_category', 'job_id', 'category_id');
    }
}
