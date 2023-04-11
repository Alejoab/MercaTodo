<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'document',
            'document_type',
            'phone',
            'address',
            'city_id'
        ];

    protected $casts
        = [
            'document_type' => DocumentType::class
        ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
