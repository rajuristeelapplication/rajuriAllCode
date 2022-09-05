<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CurlCallHelper;

class TrackLocation extends CustomModel
{
    use HasFactory;

    protected $table = 'trackLocation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId','latitude','longitude','meter','webMeter','lessthen100Min','webKm','webEvent','startTime','endTime'
    ];


    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
      }


      public static function calculateDistanceBetweenTwoPoints($latitudeOne='', $longitudeOne='', $latitudeTwo='', $longitudeTwo='',$distanceUnit ='',$round=false,$decimalPoints='')
        {
            if (empty($decimalPoints))
            {
                $decimalPoints = '3';
            }
            if (empty($distanceUnit)) {
                $distanceUnit = 'KM';
            }
            $distanceUnit = strtolower($distanceUnit);
            $pointDifference = $longitudeOne - $longitudeTwo;
            $toSin = (sin(deg2rad($latitudeOne)) * sin(deg2rad($latitudeTwo))) + (cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * cos(deg2rad($pointDifference)));
            $toAcos = acos($toSin);
            $toRad2Deg = rad2deg($toAcos);

            $toMiles  =  $toRad2Deg * 60 * 1.1515;
            $toKilometers = $toMiles * 1.609344;
            $toNauticalMiles = $toMiles * 0.8684;
            $toMeters = $toKilometers * 1000;
            $toFeets = $toMiles * 5280;
            $toYards = $toFeets / 3;


                switch (strtoupper($distanceUnit))
                {
                    case 'ML'://miles
                            $toMiles  = ($round == true ? round($toMiles) : round($toMiles, $decimalPoints));
                            return $toMiles;
                        break;
                    case 'KM'://Kilometers
                            $toKilometers  = ($round == true ? round($toKilometers) : round($toKilometers, $decimalPoints));
                            return $toKilometers;
                        break;
                    case 'MT'://Meters
                            // $toMeters  = ($round == true ? round($toMeters) : round($toMeters, $decimalPoints));
                            $toMeters  = ($round == true) ? round($toMeters) : number_format((float)$toMeters, $decimalPoints, '.', '');
                            return $toMeters;
                        break;
                    case 'FT'://feets
                            $toFeets  = ($round == true ? round($toFeets) : round($toFeets, $decimalPoints));
                            return $toFeets;
                        break;
                    case 'YD'://yards
                            $toYards  = ($round == true ? round($toYards) : round($toYards, $decimalPoints));
                            return $toYards;
                        break;
                    case 'NM'://Nautical miles
                            $toNauticalMiles  = ($round == true ? round($toNauticalMiles) : round($toNauticalMiles, $decimalPoints));
                            return $toNauticalMiles;
                        break;
                }
        }


        public static function getAddress($latitude,$longitude)
        {
            try {

                $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$latitude&lon=$longitude";

                $response = CurlCallHelper::commonCurlCall('GET',$url,null,null);


                $title  = $response['address']['city'] ?? NULL;
                $address  = $response['display_name'] ?? NULL;

                 return ['title' => $title,'address' => $address];


                // $geocodingKey = 'AIzaSyBWF9wFs2DV5_Ywpme7CSJg4F1wWOg0yK4';

                //     //google map api url
                // $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$geocodingKey";

                // $geocode = file_get_contents($url);
                // $json = json_decode($geocode);


                // $title = $json->results[0]->address_components[2]->short_name ?? '';
                // $address = $json->results[0]->formatted_address ?? '';


                // return ['title' => $title,'address' => $address];

            }
            //catch exception
            catch(\Exception $e) {
                return ['title' => '','address' => ''];
            }
        }

    // public static function distance($lat1, $lng1, $lat2, $lng2, $radius = 6378137)
    //     {
    //         static $x = M_PI / 180;
    //         $lat1 *= $x; $lng1 *= $x;
    //         $lat2 *= $x; $lng2 *= $x;
    //         $distance = 2 * asin(sqrt(pow(sin(($lat1 - $lat2) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lng1 - $lng2) / 2), 2)));

    //         return $distance * $radius;
    //     }

}
