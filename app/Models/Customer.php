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
            'name',
            'surname',
            'document',
            'document_type',
            'phone',
            'address',
            'city_id',
            'user_id',
        ];

    protected $casts
        = [
            'document_type' => DocumentType::class
        ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
