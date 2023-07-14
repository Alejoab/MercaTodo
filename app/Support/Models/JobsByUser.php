<?php

namespace App\Support\Models;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use App\Support\QueryBuilders\JobsByUserQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int               $id
 * @property int               $user_id
 * @property JobsByUserType    $type
 * @property ?JobsByUserStatus $status
 * @property array             $errors
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

    public function newEloquentBuilder($query): JobsByUserQueryBuilder
    {
        return new JobsByUserQueryBuilder($query);
    }
}
