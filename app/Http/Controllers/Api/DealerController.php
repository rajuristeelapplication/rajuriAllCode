<?php

namespace App\Http\Controllers\Api;

use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DealerValidation;
use App\Http\Requests\Api\DealerValidationGet;


class DealerController extends Controller
{
    /**
     * get Random Number
     *
     * @return json
     */

    public function getDealerId(Request $request)
    {
        $user = \Auth::user();
        $randomNumber = UtilityHelper::generateUniqueCode('dealers', 'dealerId');

        return $this->toJson([
            'getDealerId' => $randomNumber,
        ]);
    }

    /**
     * Create Add Update  Dealer
     *
     * @param  DealerValidation $request
     *
     * @return json
     */

    public function addDealer(DealerValidation $request)
    {
            $user = \Auth::user();

            $dealerRecord = new Dealer();

            if(!empty($request->id))
            {
                $dealerRecord =  Dealer::where(['id' => $request->id,'userId' => $user->id])->first();
                if(empty($dealerRecord))
                {
                    return $this->toJson([],trans('api.dealer.not_found'),0);
                }
            }
            $dealerRecord->userId = $user->id;
            $dealerRecord->fill($request->all());

            if($request->fType != "Dealer" && $request->dob == "null")
            {
                $dealerRecord->dob = null;
            }
            $dealerRecord->save();

            return $this->toJson([
                'dealerRecord' => $dealerRecord,
            ], trans('api.dealer.success',['fType' => $dealerRecord->fType,'type' => $request->dealerStatus]));
    }

     /**
     * Dealer Delete
     *
     * @param string  id (dealerId)
     *
     * @return json
     */

    public function deleteDealer(Request $request)
    {
        $user = \Auth::user();

        $dealerRecord =  Dealer::where(['id' => $request->id,'userId' => $user->id])->first();

        if(empty($dealerRecord))
            {
                return $this->toJson([],trans('api.dealer.not_found'),0);
            }

       $dealerRecord->delete();

       return $this->toJson([], trans('api.dealer.success',['fType' => $dealerRecord->fType,'type' => 'Delete']));

    }

    /**
     * Get Dealer Information
     *
     * @param  DealerValidation $request
     *
     * (optional parameters)
     *
     * @param  string fType
     * @param  string sort
     * @param  string stateId
     * @param  string cityId
     * @param  string talukaId
     * @param  string dob
     * @param  string search
     * @param  string searchFirmName
     * @param  string getDealerSchedule  (pass here any string get his user id record)
     * @param  string currentLatitude
     * @param  string currentLongitude
     * @return json
     */

    public  function getDealers(DealerValidationGet $request)
    {


        $dealerPagination = !empty($request->paginationRecord) ? $request->paginationRecord : config('constant.dealerPagination');

        $user = \Auth::user();

        if(!empty($currentLatitude) &&  !empty($currentLongitude))
        {
            $dealerPagination = 20000;
        }

        $userId = $user->id;

        $fType = isset($request->fType) ? $request->fType  : [];
        $dType = $request->dType ?? '';

        $dobStart = $request->dobStart ?? '';
        $dobEnd = $request->dobEnd ?? '';


        $sort = $request->sort ?? '';
        $stateId = $request->stateId ?? '';
        $cityId = $request->cityId ?? '';
        $talukaId = $request->talukaId ?? '';
        $dob = $request->dob ?? '';
        $search = $request->search ?? '';
        $searchFirmName = $request->searchFirmName ?? '';

        $getDealerSchedule = $request->getDealerSchedule ?? '';
        $currentLatitude = $request->currentLatitude ?? 0;
        $currentLongitude = $request->currentLongitude ?? 0;

        $rjDealerId = $request->rjDealerId ?? '';

        $radius = config('constant.dealerRadius');



        $getDealers = Dealer::getSelectQuery()->selectRaw("dealers.userId,IF(dealers.userId = '$userId',1,0) as isOwnDealer")

        ->when(!empty($currentLatitude) &&  !empty($currentLongitude), function ($query) use ($request,$userId,$radius,$rjDealerId) {
                return $query->selectRaw("( 6371 * acos( cos( radians(?) ) *
                cos( radians( dealers.latitude ) )
                * cos( radians( dealers.longitude ) - radians(?)
                ) + sin( radians(?) ) *
                sin( radians( dealers.latitude ) ) )
            ) AS distance", [$request->currentLatitude, $request->currentLongitude, $request->currentLatitude])
            ->where('dealers.id','!=',$rjDealerId)
            ->where('dealers.fType','Dealer')
            ->having("distance", "<", $radius);
        })

        // ->when(empty($getDealerSchedule), function ($query) use ($userId) {
        //     $query->where(['userId' => $userId]);
        // })
        ->when(!empty($fType), function ($query) use ($request) {
            return $query->whereIn('dealers.fType',  $request->fType);
        })

        ->when(!empty($dType), function ($query) use ($request) {
            return $query->where('dealers.dType',  $request->dType);
        })

        ->when(!empty($stateId), function ($query) use ($request) {
            return $query->whereIn('dealers.stateId',  $request->stateId);
        })
        ->when(!empty($cityId), function ($query) use ($request) {
            return $query->whereIn('dealers.cityId',  $request->cityId);
        })
        ->when(!empty($talukaId), function ($query) use ($request) {
            return $query->whereIn('dealers.talukaId',  $request->talukaId);
        })
        ->when(!empty($dobStart) && !empty($dobEnd) , function ($query) use ($request,$dobStart,$dobEnd) {

            $query =  $query->whereDate('dealers.dob', '>=', $dobStart)
            ->whereDate('dealers.dob', '<=', $dobEnd);
        })
        ->when(!empty($search), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->where('name', "like", "%" . $request->search . "%");
            });
        })
        ->when(!empty($searchFirmName), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $query->where('dealers.firmName', "like", "%" . $request->searchFirmName . "%");
            });
        })
        ->when(!empty($sort), function ($query) use ($sort) {

            if($sort == 'asc' || $sort == 'desc')
            {
                return $query->orderBy('dealers.name',  $sort);
            }

            if($sort == 'Main Dealer' || $sort == 'Sub Dealer')
            {
                return $query->where('dealers.dType',  $sort);
            }

        })
            ->where('statusDealers','Approved')
            ->orderBy('dealers.createdAt','desc')
            ->paginate($dealerPagination);


        return $this->toJson([
            'hasMore' => $getDealers->hasMorePages(),
            'totalCount' => $getDealers->total(),
            'getDealers' => $getDealers->items(),
        ]);



    }

     /**
     * Get Dealer Details
     *
     * @param  string id (dealer id)
     *
     * @return json
     */


    public  function dealerDetails(Request $request)
    {
        $id = $request->id ?? '';
        $dealerDetails =  Dealer::dealerDetails()->where('dealers.id',$id)->first();

        if(empty($dealerDetails))
        {
            return $this->toJson([],trans('api.dealer.not_found'),0);
        }

        return $this->toJson([
            'dealerDetails' => $dealerDetails,
        ]);
    }


}
