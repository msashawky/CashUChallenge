<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HotelRepositoryInterface
{
    public function searchForHotels(Request $request);


}
