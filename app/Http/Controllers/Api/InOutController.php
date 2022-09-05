<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\InOut;
use App\Models\TrackLocation;
use App\Models\TrackLocationAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InOutValidation;


class InOutController extends Controller
{
    /**
     * Get getInOutStatus.
     *
     * @return json
     *
     * 0 => In,
     * 1 => Out,
     * 2 => Done,
     */

    public function getInOutStatus(Request $request)
    {
        $user = \Auth::user();

        $status = InOut::checkStatus($user->id);

        return $this->toJson([
            'userInOutStatus' => $status['status'],
            'userInOutDetails' => $status['details'],
        ]);
    }


    /**
     * User In Out of The Day
     *
     * @param  InOutValidation $request
     *
     * @return json
     */

    public function InOutUser(InOutValidation $request)
    {
        $user = \Auth::user();
        $now = Carbon::now()->format('Y-m-d');

        try {

            $status = InOut::checkStatus($user->id);

            if ($status['status'] == 2) {
                return $this->toJson([
                    'userInOutStatus' => $status['status'],
                    'userInOutDetails' => $status['details'],
                ], trans('api.in_out.out_already'), 0);
            }

            if ($status['status'] == 0) {
                $inOutRecord = new InOut();
            }

            if ($status['status'] == 1) {
                $inOutRecord =  InOut::checkUserTodayDateInOut($user->id);
            }
            $inOutRecord->userId = $user->id;
            $inOutRecord->date = $now;
            $inOutRecord->fill($request->all());
            $inOutRecord->save();

            $status = InOut::checkStatus($user->id);

            $InOut = empty($inOutRecord->outAddress) ? 'In': 'Out';

            $message = "You're {$InOut}";

            if(!empty($request->outDateTime))
            {
                $inDateTime =  InOut::where(['id' => $inOutRecord->id])->first()->inDateTime;

                $outDateTime = \Carbon\Carbon::parse($request->outDateTime)->format('Y-m-d H:s:i');

                $to =   \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $inDateTime);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $outDateTime);
                $diffInHours = $to->diffInHours($from);

                InOut::where(['id' => $inOutRecord->id])->update(['diffHours' => $diffInHours]);

                // $trackLocation = new TrackLocation();
                // $trackLocation->userId = $user->id;
                // $trackLocation->latitude = $request->outLatitude;
                // $trackLocation->longitude = $request->outLongitude;
                // $trackLocation->startTime = date('H:i:s');
                // $trackLocation->save();

                $date = date('Y-m-d');

                $checkPreviousEvent = TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','desc')->first();

                if(!empty($checkPreviousEvent))
                {

                    $endDateTime = date('Y-m-d H:i:s');

                    $startTime = Carbon::parse($checkPreviousEvent->startDateTime);
                    $finishTime = Carbon::parse($endDateTime);

                    $differenceInMinutes = $finishTime->diffInMinutes($startTime);

                    $checkPreviousEvent->endDateTime = date('Y-m-d H:i:s');
                    $checkPreviousEvent->diffMinutes = $differenceInMinutes;
                    $checkPreviousEvent->save();
                }

                $track = new TrackLocationAddress();
                $track->event = 'PUNCH_OUT';
                $track->userId = $user->id;
                $track->latitude = $request->outLatitude;
                $track->longitude = $request->outLongitude;
                $track->startDateTime = date('Y-m-d H:i:s');
                $track->endDateTime = date('Y-m-d H:i:s');
                $track->address = $request->outAddress ?? NULL;
                $track->save();


                // $distanceKm= TrackLocationAddress::where(['userId' => $user->id,'event' => 'TRAVEL'])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');
                // $diffMinutes= TrackLocationAddress::where(['userId' => $user->id,'event' => 'TRAVEL'])->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');

                $distanceKm= TrackLocationAddress::where(['userId' => $user->id])
                ->whereIn('event', ['TRAVEL','GPS_OFF'])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');

                $diffMinutes= TrackLocationAddress::where(['userId' => $user->id])
                        ->whereIn('event', ['TRAVEL','GPS_OFF'])
                        ->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');


                InOut::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date])->update(['travelKm' => $distanceKm,'travelMinutes' => $diffMinutes ]);


               $updateLoc =  TrackLocation::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date])->orderby('createdAt','desc')->first();
               $updateLoc->webEvent  = 'PUNCH_OUT';
               $updateLoc->lessthen100Min = 0;
               $updateLoc->save();

            }else{

                // $trackLocation = new TrackLocation();
                // $trackLocation->userId = $user->id;
                // $trackLocation->latitude = $request->inLatitude;
                // $trackLocation->longitude = $request->inLongitude;
                // $trackLocation->startTime = date('H:i:s');
                // $trackLocation->save();

                $track = new TrackLocationAddress();
                $track->event = 'PUNCH_IN';
                $track->userId = $user->id;
                $track->latitude = $request->inLatitude;
                $track->longitude = $request->inLongitude;
                $track->startDateTime = date('Y-m-d H:i:s');
                $track->address = $request->inAddress ?? NULL;
                $track->save();
            }


            return $this->toJson([
                'title' => "Data recorded",
                'userInOutStatus' => $status['status'],
                'userInOutDetails' => $status['details'],
            ], $message, 1);
        } catch (\Exception $e) {
            return $this->toJson([],  $e->getMessage(), 0);
        }
    }
}
