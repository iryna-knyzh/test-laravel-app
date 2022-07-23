<?php

namespace App\Http\Requests;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public const MAX_RANGE_DATES_NUMBER = 24;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_id' => [
                'required',
                'integer',
                Rule::in(Location::query()->pluck('id')),
            ],
            'volume' => [
                'required',
                'numeric',
            ],
            'temperature' => [
                'required',
                'numeric',
            ],
            'start_date' => [
                'required',
                'string',
                'date',
                'date_format:Y-m-d H:i:s',
            ],
            'end_date' => [
                'required',
                'string',
                'date',
                'date_format:Y-m-d H:i:s',
            ],
            'calculated_blocks_amount' => [
                'required',
                'integer',
            ],
            'calculated_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.9999',
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $timezone = Location::find($this->input('location_id'))->timezone->value;

            $startDate = $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->input('start_date'), $timezone);
            $endDate = $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->input('end_date'), $timezone);

            if ($startDate->diff($endDate)->days > self::MAX_RANGE_DATES_NUMBER) {
                $validator->errors()->add("end_date", "Date range more than " . self::MAX_RANGE_DATES_NUMBER . " days");
            }
        });
    }
}
