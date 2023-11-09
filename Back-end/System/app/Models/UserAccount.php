<?php

namespace App\Models;

use App\Filters\UserAccount\UserAccountFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccount extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'user_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at',
    ];

    public static function filter($request, $builder): Builder
    {
        return (new UserAccountFilter($request))->apply($builder);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'id', 'id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class, 'user_id', 'id');
    }

    public function cvs(): HasMany
    {
        return $this->hasMany(CV::class, 'user_id', 'id');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(UserExperience::class, 'user_id', 'id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'user_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class, 'user_id', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'user_id', 'id');
    }

    public function job_reports(): HasMany
    {
        return $this->hasMany(JobReport::class, 'user_id', 'id');
    }

    public function company_reports(): HasMany
    {
        return $this->hasMany(CompanyReport::class, 'user_id', 'id');
    }

    public function post_comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'user_id', 'id');
    }

    public function time_table(): HasOne
    {
        return $this->hasOne(TimeTable::class, 'user_id', 'id');
    }

    public function user_history(): HasMany
    {
        return $this->hasMany(UserHistory::class, 'user_id', 'id');
    }

    public function saved_jobs(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'user_id', 'id');
    }
}
