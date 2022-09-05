<?php

namespace App\Http\Controllers\Api;

use App\Models\InOut;
use App\Models\TrackLocation;
use App\Models\TrackLocationAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TrackController extends Controller
{

    /**
     * Create  Track Location
     *
     * @param Request $request
     *
     * @return json
     */

    public function trackLocation(Request $request)
    {
        $this->validate($request, [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);


        $user = \Auth::user();
        $date = date('Y-m-d');

        $checkPreviousRecord = TrackLocation::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','desc')->first();
        $checkPreviousEvent = TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','desc')->first();

        $webMeter = 0;
        $webKm = 0;
        $webEvent = 'PUNCH_IN';
        $lessthen100Min = 0;

        $currentDateTime = date('Y-m-d H:i:s');

        if(!empty($checkPreviousRecord))
        {
            $checkPreviousRecord->endTime = date('H:i:s');
            $checkPreviousRecord->save();

            // code start

            $startTime = Carbon::parse($checkPreviousRecord->createdAt);
            $finishTime = Carbon::parse($currentDateTime);

            $differenceInSeconds = $finishTime->diffInSeconds($startTime);
            $differenceInMinutes = $finishTime->diffInMinutes($startTime);

            $diffMeter = TrackLocation::calculateDistanceBetweenTwoPoints($checkPreviousRecord->latitude,$checkPreviousRecord->longitude,$request->latitude,$request->longitude,'MT',false,4);
            $webMeter = $diffMeter;

            try {
                $webKm =$webMeter/1000;
            }
            catch(\Exception $e) {
                $webKm = 0.0;
            }

            // $webKm =$webMeter/1000;


            $webKm = number_format((float)$webKm, 4, '.', '') ?? 0;


            if($diffMeter < 100)
            {
                $webEvent = 'UNPLANNED_STOP';
                $lessthen100Min = 1;
            }
            else{
                $webEvent = 'TRAVEL';
            }

            if($differenceInSeconds >= 300)
            {
                $webEvent = 'GPS_OFF';
            }


            if($differenceInSeconds <= 40 && $webKm > 1.2 )
            {
                return $this->toJson([], 'Km Is Wrong', 1);
            }

            if($webEvent == 'TRAVEL' && $webKm > 2 )
            {
                return $this->toJson([], 'Km Is Wrong', 1);
            }
        }

        $track = new TrackLocation();
        $track->userId = $user->id;
        $track->startTime = date('H:i:s');
        $track->webMeter = $webMeter;
        $track->webKm = $webKm;
        $track->lessthen100Min = $lessthen100Min;
        $track->webEvent = $webEvent;
        $track->fill($request->all());

        if($track->save())
        {
            if($webEvent == "UNPLANNED_STOP")
            {
                $checkPreviousRecord = TrackLocation::where(['userId' => $user->id,'isCal' => 1])->whereRaw('date(createdAt) = ?', [$date] )->first();

                    if(empty($checkPreviousRecord))
                    {
                        $track->isCal = 1;
                        $track->save();
                    }

            }else{
                TrackLocation::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->update(['isCal' => 0]);
            }

            if($webEvent == "GPS_OFF")
            {
                $startDatePreviousRecordStartDate = $checkPreviousRecord->createdAt ?? $currentDateTime;

                $this->eventInsert($webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm,0,$startDatePreviousRecordStartDate);
                return $this->toJson([], 'Success');
            }

            // Event Address Manage

            if(!empty($checkPreviousRecord))
            {
                    if($diffMeter < 100)
                    {
                        $previousCheckUnPlannedStopEvent =  TrackLocation::where(['userId' => $user->id,'isCal' => 1])
                                                                            ->whereRaw('date(createdAt) = ?', [$date] )
                                                                            ->orderBy('createdAt','desc')->first();

                        $differenceInSeconds = 0;

                        if(!empty($previousCheckUnPlannedStopEvent))
                        {
                            $startTime = Carbon::parse($previousCheckUnPlannedStopEvent->createdAt);
                            $finishTime = Carbon::parse($currentDateTime);

                            $differenceInMinutes = $finishTime->diffInMinutes($startTime);
                            $differenceInSeconds = $finishTime->diffInSeconds($startTime);
                        }



                        if($differenceInSeconds > 300)
                        {
                            if($checkPreviousEvent->event != 'UNPLANNED_STOP')
                            {
                                $webEvent = 'UNPLANNED_STOP';
                                $this->eventInsert($webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm,1);
                            }
                            else{
                                $this->eventUpdate($checkPreviousEvent->id,$webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                            }

                            TrackLocation::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->update(['isCal' => 0]);

                        }else{

                            if($checkPreviousEvent->event == 'TRAVEL')
                            {
                                $webEvent = 'TRAVEL';

                                $this->eventUpdate($checkPreviousEvent->id,$webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                            }else{

                                $webEvent = 'UNPLANNED_STOP';

                                if($checkPreviousEvent->event == 'UNPLANNED_STOP')
                                {
                                    $this->eventUpdate($checkPreviousEvent->id,$webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                                }
                                else{
                                    $this->eventInsert($webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                                }
                            }
                        }
                    }
                else{

                    $webEvent = 'TRAVEL';

                    if($checkPreviousEvent->event != 'TRAVEL')
                    {
                        $this->eventInsert($webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                    }
                    else{

                        $this->eventUpdate($checkPreviousEvent->id,$webEvent,$request->latitude,$request->longitude,$request->title,$request->address,$track->webKm);
                    }
                }

            }

            return $this->toJson([], 'Success');
        }

        return $this->toJson([], 'Error', 0);
    }

    public function eventInsert($webEvent,$latitude,$longitude,$title,$address,$km,$isTravelEvent = 0,$startDatePreviousRecordStartDate = 0)
    {
        $newTime = strtotime('-4 minutes');

        $startDateTimeInsert = date('Y-m-d H:i:s');

        $getAddress = TrackLocation::getAddress($latitude,$longitude);

        $user = \Auth::user();

        $date = date('Y-m-d');

        $checkPreviousEvent = TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','desc')->first();

        if(!empty($checkPreviousEvent))
        {
            if($isTravelEvent == 1 && $checkPreviousEvent->event == "TRAVEL")
            {
                $startDateTimeInsert = date('Y-m-d H:i:s',$newTime);
                $endDateTime = date('Y-m-d H:i:s',$newTime);
            }else{
                $endDateTime = date('Y-m-d H:i:s');
            }

            if($webEvent == "GPS_OFF")
            {
                $endDateTime = $startDatePreviousRecordStartDate;
                $startDateTimeInsert = $startDatePreviousRecordStartDate;
            }

            if($checkPreviousEvent->event != "GPS_OFF")
            {
                $startTime = Carbon::parse($checkPreviousEvent->startDateTime);
                $finishTime = Carbon::parse($endDateTime);

                $differenceInMinutes = $finishTime->diffInMinutes($startTime);

                $checkPreviousEvent->endDateTime = $endDateTime;
                $checkPreviousEvent->diffMinutes = $differenceInMinutes;
                $checkPreviousEvent->save();
            }
        }

        $track = new TrackLocationAddress();
        $track->userId = $user->id;
        $track->event = $webEvent;
        $track->latitude = $latitude;
        $track->longitude = $longitude;
        $track->title = $getAddress['title'];
        $track->address = $getAddress['address'];
        // $track->title = $title;
        // $track->address = $address;
        $track->startDateTime = $startDateTimeInsert;
        $track->distanceKm = $km;
        $track->save();

        if($track->event == "GPS_OFF")
        {
                $endDateTime = date('Y-m-d H:i:s');
                $startTime = Carbon::parse($track->startDateTime);
                $finishTime = Carbon::parse($endDateTime);
                $differenceInMinutes = $finishTime->diffInMinutes($startTime);
                $track->endDateTime = $endDateTime;
                $track->diffMinutes = $differenceInMinutes;
                $track->save();
        }


        $distanceKm= TrackLocationAddress::where(['userId' => $user->id])
                                         ->whereIn('event', ['TRAVEL','GPS_OFF'])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');
        $diffMinutes= TrackLocationAddress::where(['userId' => $user->id])
                                    ->whereIn('event', ['TRAVEL','GPS_OFF'])
                                    ->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');

        InOut::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date])->update(['travelKm' => $distanceKm,'travelMinutes' => $diffMinutes ]);

        return true;
    }

    public function eventUpdate($id,$webEvent,$latitude,$longitude,$title,$address,$km)
    {
        $user = \Auth::user();
        $date = date('Y-m-d');
        $track  = TrackLocationAddress::where(['id' => $id,'userId' => $user->id])->first();

        if(!empty($track))
        {
            $track->event = $webEvent;
            $track->latitude = $latitude;
            $track->longitude = $longitude;
            // $track->title = $title;
            // $track->address = $address;
            $track->distanceKm = $track->distanceKm + $km ?? 0;
            $track->save();
        }


        $distanceKm= TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');
        $diffMinutes= TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');

        $distanceKm= TrackLocationAddress::where(['userId' => $user->id])
                    ->whereIn('event', ['TRAVEL','GPS_OFF'])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');
        $diffMinutes= TrackLocationAddress::where(['userId' => $user->id])
                    ->whereIn('event', ['TRAVEL','GPS_OFF'])
                    ->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');


         InOut::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date])->update(['travelKm' => $distanceKm,'travelMinutes' => $diffMinutes ]);

        return true;
    }


     /**
     * Create  Track Location Event
     *
     * @param Request $request
     *
     * @return json
     */

    public function trackLocationEvent(Request $request)
    {
        return true;

        $user = \Auth::user();
        $date = date('Y-m-d');

        $startDateTime = date('Y-m-d H:i:s');

        if($request->event == "UNPLANNED_STOP")
        {
            $startDateTime = Carbon::parse($startDateTime)->subMinutes(3);
        }

        $checkPreviousRecord = TrackLocationAddress::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date] )->orderBy('createdAt','desc')->first();

        $checkPreviousRecordTravelEvent = TrackLocationAddress::where(['userId' => $user->id,'event' => 'TRAVEL'])
                                                                ->whereRaw('date(createdAt) = ?', [$date] )
                                                                ->orderBy('createdAt','desc')->first()->distance ?? 0;


        if(!empty($checkPreviousRecord))
        {
            // $checkPreviousRecord->endDateTime = date('Y-m-d H:i:s');
            $checkPreviousRecord->endDateTime = $startDateTime;

            $distance =  ($request->distance - $checkPreviousRecordTravelEvent);

            $checkPreviousRecord->distance = $distance;

            if(!empty($request->distance))
            {
                $checkPreviousRecord->distanceKm = round($distance/1000,3);
                // $checkPreviousRecord->distanceKm = round($request->distance/1000,3);
            }

            $startTime = Carbon::parse($checkPreviousRecord->startDateTime);
            $endTime = Carbon::parse($checkPreviousRecord->endDateTime);

            $diffMinutes = $startTime->diffInMinutes($endTime);

            $checkPreviousRecord->diffMinutes = $diffMinutes;
            $checkPreviousRecord->save();
        }

        $track = new TrackLocationAddress();
        $track->userId = $user->id;
        $track->startDateTime = $startDateTime;
        $track->fill($request->all());
        // if(!empty($request->distance))
        // {
        //     $track->distanceKm = round($request->distance/1000);
        // }

        if($track->save())
        {

           $distanceKm= TrackLocationAddress::where(['userId' => $user->id,'event' => 'TRAVEL'])->whereRaw('date(createdAt) = ?', [$date] )->sum('distanceKm');
           $diffMinutes= TrackLocationAddress::where(['userId' => $user->id,'event' => 'TRAVEL'])->whereRaw('date(createdAt) = ?', [$date] )->sum('diffMinutes');

            InOut::where(['userId' => $user->id])->whereRaw('date(createdAt) = ?', [$date])->update(['travelKm' => $distanceKm,'travelMinutes' => $diffMinutes ]);
            return $this->toJson([], 'Success');
        }

        return $this->toJson([], 'Error', 0);
    }

}
