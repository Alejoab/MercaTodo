<?php

namespace App\Domain\Customers\Models;

use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 *
 * @method static DepartmentFactory factory(...$parameters)
 */
class Department extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected static function newFactory(): Factory
    {
        return DepartmentFactory::new();
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
