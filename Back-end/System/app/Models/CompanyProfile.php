<?php

namespace App\Models;

use App\Filters\CompanyProfile\CompanyProfileFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'company_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'logo',
        'description',
        'site',
        'address',
        'size',
        'phone',
        'email',
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
        return (new CompanyProfileFilter($request))->apply($builder);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CompanyAccount::class, 'id', 'id');
    }

    public function jobs(): HasManyThrough
    {
        return $this->hasManyThrough(Job::class, EmployerProfile::class, 'company_id', 'employer_id', 'id', 'id');
    }
}
