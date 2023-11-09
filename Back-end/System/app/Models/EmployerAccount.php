<?php

namespace App\Models;

use App\Filters\EmployerAccount\EmployerAccountFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmployerAccount extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'employer_accounts';

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
        return (new EmployerAccountFilter($request))->apply($builder);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'employer_id', 'id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class, 'id', 'id');
    }
}
