<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Models\Cities;
use App\Models\Talukas;
use App\Models\Regions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StateCityController extends Controller
{
    /**
     * Get States.
     *
     * @return json
     */

    public function getStates(Request $request)
    {
        $statePagination = 40;

        $getStates = State::getSelectQuery()->isActive()->join('cities','cities.stateId','states.id')->orderBy('states.sName','asc')->groupBy('states.id')->paginate($statePagination);

        return $this->toJson([
            'getStates' => $getStates->items(),
            'hasMore' => $getStates->hasMorePages()
        ]);
    }

     /**
     * Get Cities.
     *
     * @param string $stateId
     *
     * @return json
     */
    public function getCities(Request $request)
    {
        $cityPagination = 8000;

        $stateIdArray = $request->stateIdArray;

        $getCities = Cities::getSelectQuery()->isActive()
                    ->join('talukas','talukas.cityId','cities.id')
                    ->when(!empty($request->stateId), function ($query) use ($request) {
                            return $query->where('cities.stateId',  $request->stateId);
                        })
                        ->when(!empty($stateIdArray), function ($query) use ($stateIdArray) {
                            return $query->whereIn('cities.stateId',  $stateIdArray);
                        })
                     ->orderBy('cities.cName','asc')
                    ->groupBy('cities.id')
                   ->paginate($cityPagination);

        return $this->toJson([
            'getCities' => $getCities->items(),
            'hasMore' => $getCities->hasMorePages()
        ]);
    }

     /**
     * Get Taluka.
     *
     * @param string $cityId
     *
     * @return json
     */
    public function getTalukas(Request $request)
    {
        $talukaPagination = 8000;

        $cityIdArray = $request->cityIdArray;

        $getTalukas = Talukas::getSelectQuery()->isActive()
                ->when(!empty($request->cityId), function ($query) use ($request) {
                        return $query->where('talukas.cityId',  $request->cityId);
                    })
                    ->when(!empty($cityIdArray), function ($query) use ($cityIdArray) {
                        return $query->whereIn('talukas.cityId',  $cityIdArray);
                    })
                    ->orderBy('talukas.tName','asc')
                   ->paginate($talukaPagination);

        return $this->toJson([
            'getTalukas' => $getTalukas->items(),
            'hasMore' => $getTalukas->hasMorePages()
        ]);
    }


    /**
     * Get Regions.
     *
     * @return json
     */

    public function getRegions(Request $request)
    {
        $regionsPagination = 20;

        $getRegions = Regions::getSelectQuery()
                // ->when(!empty($request->stateId), function ($query) use ($request) {
                //         return $query->where('regions.stateId',  $request->stateId);
                //     })
                   ->paginate($regionsPagination);

        return $this->toJson([
            'getRegions' => $getRegions->items(),
            'hasMore' => $getRegions->hasMorePages()
        ]);
    }

}
