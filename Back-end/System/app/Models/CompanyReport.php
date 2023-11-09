<?php

namespace App\Models;

use App\Filters\CompanyReport\CompanyReportFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'company_reports';

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
        return (new CompanyReportFilter($request))->apply($builder);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyAccount::class, 'company_id', 'id');
    }

    public function company_profile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'id');
    }

    public function user_profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'id');
    }
}
