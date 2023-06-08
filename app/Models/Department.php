<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 */
class Department extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
