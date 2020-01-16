<?php

namespace App\Repositories;
use App\Traits\ApiResponseTrait;
use App\Interfaces\HotelRepositoryInterface;
use Illuminate\Http\Request;


//HotelRepository Class is implementing HotelRepositoryInterface Methods

class HotelRepository implements HotelRepositoryInterface
{
    use ApiResponseTrait; //Trait for Handling Our API Responses

    public function searchForHotels(Request $request)
    {
        $path = storage_path() . "\json\hotels.json";//Hotels JSON File

        $jsonRequest = file_get_contents($path);
        $decodedRequest = json_decode($jsonRequest);//Decoding JSON
        $matched = array();//Empty Array
        foreach ($decodedRequest->hotels as $hotel)
        {//Check for the filled fields as required
            if(filled($request->from_date) && filled($request->to_date) && filled($request->city) && filled($request->adults_number)){
                    array_push($matched, $hotel);
                    }
        }
        return $matched;
    }


    //For BestHotels API
    public function bestHotels(Request $request)
    {
        $path = storage_path() . "\json\hotels.json";//Hotels JSON File
        $jsonRequest = file_get_contents($path);
        $decodedRequest = json_decode($jsonRequest);//Decoding JSON
        $matched = array();//Empty Array
        foreach ($decodedRequest->hotels as $hotel)
        {//Check for the filled fields as required
            if(filled($request->fromDate) && filled($request->toDate) && filled($request->city) && filled($request->numberOfAdults)){
                if($hotel->provider == 'BestHotels'){
                    array_push($matched, $hotel);
                }
            }
        }
        return $matched;
    }


    //For TopHotels API
    public function topHotels(Request $request)
    {
        $path = storage_path() . "\json\hotels.json";//Hotels JSON File
        $jsonRequest = file_get_contents($path);
        $decodedRequest = json_decode($jsonRequest);//Decoding JSON
        $matched = array();//Empty Array
        foreach ($decodedRequest->hotels as $hotel)
        {//Check for the filled fields as required
            if(filled($request->from) && filled($request->to) && filled($request->city) && filled($request->adultsCount)){
                if($hotel->provider == 'TopHotel'){
                    array_push($matched, $hotel);
                }
            }
        }
        return $matched;
    }


    public function validateHotelSearch(Request $request)
    {
        return $this->apiValidation($request, [
            'to_date' => 'required',
            'to_date' => 'required|date',
            'city' => 'required|string|max:100',
            'adults_number' => 'required|number',
        ]);
    }



}
