<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\VoiceRecording;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminVoiceRecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userNameList = VoiceRecording::selectRaw('users.id,users.fullName,users.roleId')
            ->join('users','users.id','voice_recordings.userId')
            ->distinct('users.fullName')
            ->whereIn('roleId',User::whichUserLogin())
            ->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('voice_recordings.userId',  User::getMarketingAdminEmployee());
            })
            ->orderByRaw('fullName ASC')
            ->get();

        return view('admin.voiceRecording.list', compact('userNameList'));
    }



      /**
     * Search OR Sorting Voice Recording (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {
        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = VoiceRecording::getSelectQuery()
                    ->selectRaw('users.fullName as createdBy,users.roleId,voice_recordings.createdAt, DATE_FORMAT(voice_recordings.createdAt, "' . config('constant.in_out_date_time_format') . '") as created_at_format')
                    ->join('users', 'users.id', 'voice_recordings.userId');

            if (!empty($request->employeeType)) {
                $query = $query->where('users.id', '=', $request->employeeType);
            }

            if(\Auth::user()->roleId == config('constant.ma_id'))
            {
                $query = $query->where('roleId','=',config('constant.marketing_executive_id'));

                $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                    return $query->whereIn('voice_recordings.userId',  User::getMarketingAdminEmployee());
                });
            }



            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%');
            });

            $voiceRecordings = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $voiceRecordings['recordsFiltered'] = $voiceRecordings['recordsTotal'] = $voiceRecordings['total'];

            foreach ($voiceRecordings['data'] as $key => $voiceRecording) {

                $params = [
                    'voiceRecordings' => $voiceRecording['id'],
                ];

                $voiceRecordings['data'][$key]['sr_no'] = $startNo + $key;


                $deleteRoute = route('voiceRecordings.destroy', $params);
                $voiceRecordings['data'][$key]['createdBy'] = $voiceRecording['createdBy'] ? ucfirst($voiceRecording['createdBy']): '-';
                $voiceRecordings['data'][$key]['vrName'] = !empty($voiceRecording['vrName']) ? '<audio controls="" style="vertical-align: middle" src="'.$voiceRecording['vrName'].'" type="audio/m4a"  controlslist="nodownload">Your browser does not support the audio element.</audio>' : '-';
                $voiceRecordings['data'][$key]['createdAt'] = $voiceRecording['createdAt'];
                $voiceRecordings['data'][$key]['created_at_format'] = $voiceRecording['created_at_format'];
                $voiceRecordings['data'][$key]['action'] = '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';

            }
            return response()->json($voiceRecordings);
        }
    }


    /**
     * Change status of the voice recording.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $voiceRecording = VoiceRecording::find($id);

        if (empty($voiceRecording)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'voice recording']), 0);
        }

        $voiceRecording->isActive = !$voiceRecording->isActive;
        $status = '';
        if ($voiceRecording->save()) {
            $status = $voiceRecording->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'voice recording', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'voice recording', 'type' => $status]), 0);
    }


  /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $voiceRecordingDetail = VoiceRecording::getSelectQuery()
        ->selectRaw('users.fullName as createdBy, DATE_FORMAT(in_outs.inDateTime, "' . config('constant.in_out_date_time_format') . '") as inDateTime, DATE_FORMAT(in_outs.outDateTime, "' . config('constant.in_out_date_time_format') . '") as outDateTime')
        ->join('users', 'users.id', 'in_outs.userId')
        ->where('in_outs.id',$id)
        ->first();

        if (!empty($voiceRecordingDetail)) {
          return view('admin.voiceRecording.view',compact('voiceRecordingDetail'));
        }

        return redirect(route('voiceRecordings.index'))->with('error', trans('messages.voice recording.not_found', ['module' => 'voice recording']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $voiceRecording = VoiceRecording::where('id', $id)->first();
        if ($voiceRecording) {



            $imageName1 = config('constant.voice_recording_image') .'/'.$voiceRecording->vrName;
            User::bucketRemoveImage($imageName1);


            $voiceRecording->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'voice recording']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'voice recording']), 0);
    }
}
