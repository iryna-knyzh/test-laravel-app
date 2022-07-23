<?php


namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingHelper
{
    public const PRICE_PER_BLOCK = 10;
    public const SECRET_CODE_LENGTH = 12;

    public static function calculatePrice(Carbon $startDate,Carbon $endDate, int $blocksNum): int
    {
        return $startDate->diff($endDate)->days * self::PRICE_PER_BLOCK * $blocksNum;
    }

    public static function generateCode(): string
    {
        return Str::random(self::SECRET_CODE_LENGTH);
    }
}
