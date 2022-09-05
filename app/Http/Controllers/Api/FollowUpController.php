<?php

namespace App\Http\Controllers\Api;

use App\Models\FollowUp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FollowUpValidation;

class FollowUpController extends Controller
{

     /**
     * Create Follow Up
     *
     * @param  FollowUpValidation $request
     *
     * @return json
     */

    public function addFollowUp(FollowUpValidation $request)
    {
        $user       = \Auth::user();
        $userId     =  $user->id;
        $id         =  $request->id;

        $fDateTime = $request->fDate .' '.$request->fTime;
        $fReminder = $request->fReminder;

        if($fReminder > $fDateTime)
        {
            return $this->toJson([],trans('api.follow_up.reminder_date_small'),0);
        }

        $typeMsg = 'Create';

        if(!empty($id))
        {
            $followUpDetails =  FollowUp::getSelectQuery()->where(['follow_ups.id' => $id,'follow_ups.userId' => $user->id])->first();

            if(empty($followUpDetails))
            {
                return $this->toJson([],trans('api.follow_up.not_found'),0);
            }

            $typeMsg = 'Update';
        }


        $followUpRecord = FollowUp::updateOrCreate(['id' => $request->id],$request->merge(['userId' => $userId,'fDateTime' => $fDateTime])->all());

        $getFollowUps = FollowUp::getSelectQuery()
                        ->where(['follow_ups.id' => $followUpRecord->id])
                        ->orderBy('follow_ups.createdAt','desc')->first();
        return $this->toJson([
            'followUpRecord' => $getFollowUps,
        ], trans('api.follow_up.success',['type' => $typeMsg]));
    }

    /**
     *  Get Follow Information
     *
     * @return json
     */
    public function getFollowUps(Request $request)
    {

        $followUpPagination = config('constant.followUpPagination');

        $user = \Auth::user();

        $getFollowUps = FollowUp::getSelectQuery()
                        ->where(['follow_ups.userId' => $user->id])
                        ->orderBy('follow_ups.createdAt','desc')
                        ->paginate($followUpPagination);

        return $this->toJson([
            'hasMore' => $getFollowUps->hasMorePages(),
            'totalCount' => $getFollowUps->total(),
            'getFollowUps' => $getFollowUps->items(),]);

    }

   /**
     *  Follow Details
     *
     * @param  string id
     *
     * @return json
     */

    public function followUpDetails(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = \Auth::user();

        $id = $request->id ?? '';
        $followUpDetails =  FollowUp::getSelectQuery()->where(['follow_ups.id' => $id,'follow_ups.userId' => $user->id])->first();

        if(empty($followUpDetails))
        {
            return $this->toJson([],trans('api.follow_up.not_found'),0);
        }

        return $this->toJson([
            'followUpDetails' => $followUpDetails,
        ]);
    }

     /**
     *  follow up delete
     *
     *  @param  string id
     *
     * @return json
     */

    public function followUpDelete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = \Auth::user();

        $id = $request->id ?? '';
        $followUpDetails =  FollowUp::getSelectQuery()->where(['follow_ups.id' => $id,'follow_ups.userId' => $user->id])->first();

        if(empty($followUpDetails))
        {
            return $this->toJson([],trans('api.follow_up.not_found'),0);
        }

        $followUpDetails->delete();

        return $this->toJson(['followUpDetails' => $followUpDetails], trans('api.follow_up.success',['type' => 'Delete']));
    }

    /**
     *  Is Follow Up Done
     *
     *  @param  string id
     *
     *  @return json
     */


    public function isFollowUpDone(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = \Auth::user();

        $id = $request->id ?? '';
        $followUpDetails =  FollowUp::getSelectQuery()->where(['follow_ups.id' => $id,'follow_ups.userId' => $user->id])->first();

        if(empty($followUpDetails))
        {
            return $this->toJson([],trans('api.follow_up.not_found'),0);
        }

        $followUpDetails->fIsDone = 1;
        $followUpDetails->save();

        return $this->toJson(['followUpDetails' => $followUpDetails], trans('api.follow_up.success',['type' => 'Completed']));
    }

}
