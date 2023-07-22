<?php

namespace App\Support\Models;

use App\Domain\Users\Models\User;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\QueryBuilders\JobsByUserQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int               $id
 * @property int               $user_id
 * @property JobsByUserType    $type
 * @property ?JobsByUserStatus $status
 * @property array             $errors
 * @property ?string           $file_name
 * @property Carbon            $created_at
 *
 * @property User              $user
 *
 * @method static JobsByUserQueryBuilder query()
 */
class JobsByUser extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'user_id',
            'status',
            'errors',
            'type',
            'file_name',
        ];

    protected $attributes
        = [
            'errors' => '{}',
        ];

    protected $casts
        = [
            'errors' => 'array',
            'type' => JobsByUserType::class,
            'status' => JobsByUserStatus::class,
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function newEloquentBuilder($query): JobsByUserQueryBuilder
    {
        return new JobsByUserQueryBuilder($query);
    }
}
