<?php

namespace App\Domain\Products\Models;

use App\Domain\Products\Enums\ExportImportStatus;
use App\Domain\Products\Enums\ExportImportType;
use App\Domain\Products\QueryBuilders\ExportImportQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                 $id
 * @property int                 $user_id
 * @property ExportImportType    $type
 * @property ?ExportImportStatus $status
 * @property array               $errors
 *
 * @method static ExportImportQueryBuilder query()
 */
class ExportImport extends Model
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
            'type' => ExportImportType::class,
            'status' => ExportImportStatus::class,
        ];

    public function newEloquentBuilder($query): ExportImportQueryBuilder
    {
        return new ExportImportQueryBuilder($query);
    }
}
