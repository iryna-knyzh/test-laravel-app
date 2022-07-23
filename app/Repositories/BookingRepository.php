<?php

namespace App\Repositories;

use App\Helpers\BookingHelper;
use App\Models\Block;
use App\Models\BlockTypeParams;
use App\Models\Booking;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingRepository
{
    public function store(array $requestValidated)
    {
        $blockTypeParams = BlockTypeParams::where('type', BlockTypeParams::TYPE_STANDARD)->first();

        $blocksNeeded = ceil($requestValidated['volume'] / ($blockTypeParams->length * $blockTypeParams->height * $blockTypeParams->weidth));

        $location = Location::query()->find($requestValidated['location_id']);

        $timezone = $location->timezone->value;

        $startDate =  Carbon::createFromFormat('Y-m-d H:i:s', $requestValidated['start_date'], $timezone);
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s',  $requestValidated['end_date'], $timezone);

        $blocks = Block::available(
            $location,
            $requestValidated['temperature'],
            $startDate,
            $endDate
        )->take($blocksNeeded);

        throw_if($blocks->count() !== $requestValidated['calculated_blocks_amount'],
            new \Exception("Blocks not available now"));

        $price = BookingHelper::calculatePrice(
                $startDate,
                $endDate,
                $blocksNeeded
            );

        throw_if($price !== $requestValidated['calculated_price'],
            new \Exception("Price was changed"));

        $booking = Booking::create([
            'user_id' => Auth::user()->id,
            'temperature' => $requestValidated['temperature'],
            'volume' => $requestValidated['volume'],
            'start_date' => $requestValidated['start_date'],
            'end_date' => $requestValidated['end_date'],
            'code' => BookingHelper::generateCode(),
            'price' => $price,
        ]);

        $booking->attach($blocks);

        return $booking->id;
    }
}
