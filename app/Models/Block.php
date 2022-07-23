<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Block extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    /**
     * @return BelongsTo
     */
    public function blockType(): BelongsTo
    {
        return $this->belongsTo(BlockTypeParams::class, 'type', 'type');
    }

    /**
     * @return BelongsToMany
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(
            Booking::class,
            'block_booking',
            'booking_id',
            'block_id'
        );
    }

    public function scopeAvailable(Builder $query, $locationId, $temperature, $startDate, $endDate): Builder
    {
        return $query
            ->whereHas('storage', function (Builder $query) use ($locationId, $temperature) {
                $query->where('location_id', $locationId)
                    ->whereBetween('temperature', [
                        self::getTemperature($temperature - 2),
                        self::getTemperature($temperature + 2),
                    ]);
            })
            ->whereDoesntHave('booking', function (Builder $query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            });
    }

    public static function getTemperature($temperature): int
    {
        return $temperature > 0 ? 0 : $temperature;
    }
}
