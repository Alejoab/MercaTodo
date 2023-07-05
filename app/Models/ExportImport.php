<?php

namespace App\Models;

use App\Enums\ExportImportStatus;
use App\Enums\ExportImportType;
use App\QueryBuilders\ExportImportQueryBuilder;
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
