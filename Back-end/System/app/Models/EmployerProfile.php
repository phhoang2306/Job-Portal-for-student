<?php

namespace App\Models;

use App\Filters\EmployerProfile\EmployerProfileFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployerProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'employer_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    public static function filter($request, $builder): Builder
    {
        return (new EmployerProfileFilter($request))->apply($builder);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(EmployerAccount::class, 'id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyAccount::class, 'company_id', 'id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'id', 'employer_id');
    }

    public function company_profile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id', 'id');
    }
}
