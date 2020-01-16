<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BestHotelsResource extends JsonResource
{

    //for security and manipulate with data
    public function toArray($request)
    {
        return [
            'hotel' => $this->hotelName,
            'hotelRate' => $this->hotelRate,
            'hotelFare' => $this->fare,
            'roomAmenities' => $this->amenities
        ];
    }

}
