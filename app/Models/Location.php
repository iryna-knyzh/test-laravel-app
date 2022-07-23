<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function storages(): HasMany
    {
        return $this->hasMany(Storage::class, 'storage_id');
    }
}
