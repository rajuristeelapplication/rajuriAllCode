<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\VoiceRecording;
use App\Http\Controllers\Controller;

class VoiceRecordingController extends Controller
{
    /**
     * Get Voice Recording
     *
     * @return json
     *
     */

    public function getVoiceRecording(Request $request)
    {
        $voiceRecordingPagination = config('constant.voiceRecordingPagination');
        $user = \Auth::user();

        $getVoiceRecording = VoiceRecording::getSelectQuery()
                            ->where(['voice_recordings.userId' => $user->id])
                            ->orderBy('voice_recordings.createdAt','desc')
                            ->paginate($voiceRecordingPagination);

        return $this->toJson([
            'hasMore' => $getVoiceRecording->hasMorePages(),
            'totalCount' => $getVoiceRecording->total(),
            'getVoiceRecording' => $getVoiceRecording->items(),

        ]);
    }

     /**
     * Add Voice Recording
     *
     * @param Request $request
     *
     * @return json
     *
     */

    public function addVoiceRecording(Request $request)
    {
        $this->validate($request, [
            "vrName" => 'required',
        ]);

        $user       = \Auth::user();
        $userId     =  $user->id;

        $voiceRecord = VoiceRecording::create($request->merge(['userId' => $userId])->all());


        $voiceRecord = VoiceRecording::getSelectQuery()
                        ->where(['voice_recordings.id' => $voiceRecord->id])->first();

        return $this->toJson([
            'voiceRecord' => $voiceRecord,
        ], trans('api.voice_recording.success',['type' => 'Create']));

    }

      /**
     * Delete Voice Recording
     *
     * @param string $id
     *
     * @return json
     *
     */

    public function deleteVoiceRecording(Request $request)
    {
        $this->validate($request, [
            "id" => 'required',
        ]);

        $user       = \Auth::user();
        $userId     =  $user->id;

        $voiceRecord = VoiceRecording::where(['id' => $request->id,'userId' => $userId])->first();

        if(empty($voiceRecord))
        {
            return $this->toJson([],trans('api.voice_recording.not_found'),0);
        }

        $voiceRecord->delete();

        return $this->toJson([
            'voiceRecord' => $voiceRecord,
        ], trans('api.voice_recording.success',['type' => 'Delete']));

    }
}
