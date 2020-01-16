<?php

namespace App\Http\Controllers;
use App\Http\Resources\BestHotelsResource;
use App\Http\Resources\HotelResource;
use App\Repositories\HotelRepository;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    protected $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository; // Have Working through the HostelRepository
    }

    public function search(Request $request)//First Search Method by : from_date, to_date, fare, amenities
    {
        $hotels =  $this->hotelRepository->searchForHotels($request);
        if($hotels > 0) {
            return $this->apiResponse(response()->json($hotels));
        }
        else{
            return $this->notFoundResponse("No Hotels Found");
        }

    }

    public function bestHotels(Request $request)
    {
        //Get BestHotels From the Repository
        $bestHotels =  $this->hotelRepository->bestHotels($request);
        $collection = array(); // Defining an empty array for collecting objects
        if($bestHotels > 0) {
            foreach ($bestHotels as $hotel) {
                //Push to the empty array
                array_push($collection, ['hotel' => $hotel->hotelName, 'hotelRate' => $hotel->hotelRate, 'hotelFare' => number_format( round($hotel->fare, 1), 2 ),
                    'roomAmenities' => $hotel->amenities]);
            }
            return $this->apiResponse(response()->json($collection)); // Returning Collection as a json response
        }
        else{
            return $this->notFoundResponse("No Hotels Found");//Not found response
        }
    }

    public function topHotels(Request $request)
    {
        //Get BestHotels From the Repository
        $topHotels =  $this->hotelRepository->topHotels($request);
        $collection = array(); // Defining an empty array for collecting objects
        $ratingStars ="";
        if($topHotels > 0) {
            foreach ($topHotels as $hotel) {
                //Coverting Rating Values to Stars
                for ($i =0; $i< $hotel->hotelRate; $i++){$ratingStars = $ratingStars."*";}
                //Push to the empty array
                array_push($collection, ['hotelName' => $hotel->hotelName, 'rate' => $ratingStars, 'price' => number_format( round($hotel->fare, 1), 2 ),
                    'discount' => $hotel->discount, 'amenities' => $hotel->amenities]);
                $ratingStars ="";//Reset Stars to the second Iteration
            }
            return $this->apiResponse(response()->json($collection)); // Returning Collection as a json response
        }
        else{
            return $this->notFoundResponse("No Hotels Found");//Not found response
        }
    }

}
