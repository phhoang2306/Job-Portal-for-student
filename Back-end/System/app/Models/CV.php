<?php

namespace App\Models;

use App\Filters\CV\CVFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CV extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'cv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cv_name',
        'cv_path',
        'cv_note',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    public static function filter($request, $builder): Builder
    {
        return (new CVFilter($request))->apply($builder);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_id', 'id');
    }

    public function user_profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'id', 'cv_id');
    }
}
