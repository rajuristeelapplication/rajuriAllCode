<?php

namespace App\Http\Controllers\Api;

use App\Models\Complaint;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ComplaintValidation;

class ComplaintController extends Controller
{
    /**
     * Get Complains
     *
     * @param Request $request
     *
     * @return json
     */

    public  function getComplains(Request $request)
    {
        $this->validate($request, [
            "cType" => 'required|in:Quality Complaint,General Complaint',
        ]);

        $complainPagination = config('constant.complainPagination');
        $user = \Auth::user();

        $cType = $request->cType ?? '';


        $getComplains = Complaint::getSelectQuery()

            ->when(!empty($cType), function ($query) use ($request) {
                return $query->where('complaints.cType',  $request->cType);
            })

            ->where(['complaints.userId' => $user->id])
            ->paginate($complainPagination);

        return $this->toJson([
            'hasMore' => $getComplains->hasMorePages(),
            'totalCount' => $getComplains->total(),
            'getComplains' => $getComplains->items(),

        ]);
    }

    /**
     * Get Complains Details
     *
     * @param  string  id  (leadid)
     *
     * @return json
     */

    public  function complainDetails(Request $request)
    {
        $user = \Auth::user();

        $id = $request->id ?? '';
        $complainDetails =  Complaint::complaintDetails()->where(['complaints.id' => $id,'complaints.userId' => $user->id])->first();

        if(empty($complainDetails))
        {
            return $this->toJson([],trans('api.complaint.not_found'),0);
        }

        return $this->toJson([
            'complainDetails' => $complainDetails,
        ]);
    }

     /**
     * Create Complain
     *
     * @param ComplaintValidation $request
     *
     * @return json
     */

    public function createComplaint(ComplaintValidation $request)
    {

        $user       = \Auth::user();
        $userId     =  $user->id;

        $leadRecord = Complaint::create($request->merge(['userId' => $userId])->all());

        $this->materialTypeCheck($leadRecord,$request->materialType,$userId);

        NotificationHelper::complainNotification($leadRecord,1);


        return $this->toJson([
            'complaintRecord' => $leadRecord,
        ], trans('api.complaint.success'));
    }

    /**
     * material Type Check
     *
     * @param  leadRecord  (lead Record Object)
     * @param  materialType $materialType (Request user side selected material)
     * @param string  userId $userId
     * @return boolean
     */
    public function materialTypeCheck($leadRecord,$materialType,$userId)
    {
        $materialTypes = $materialType;

        $mtArray    = [];
        $mtListView = '';
        $mtTitle    = '';

        if(!empty($materialTypes))
        {
            foreach($materialTypes as $key=>$materialType)
            {
                if(!empty($materialType['totalQty']))
                {
                    $mtArray[] = [
                        'id'         => \Str::uuid(),
                        'userId'     => $userId,
                        'leadId'     => $leadRecord->id,
                        'materialId' => $materialType['id'],
                        'totalQty'   => $materialType['totalQty'],
                        'mName' => $materialType['mName'],
                        'isParent' => !empty($materialType['straightBend']) ? 1 :0,
                    ];
                    $mtListView .= $materialType['mName'] .', '.$materialType['totalQty'] .' mt.  | ';
                    $mtTitle    .=  $materialType['mName'] .',';


                        for($i=0;$i<count($materialType['straightBend']);$i++)
                        {
                            foreach($materialType['straightBend'][$i]['size'] as $key1 => $sizes)
                            {
                                if(!empty($sizes['totalQty']))
                                {
                                    $mtArray[] = [
                                        'id'         => \Str::uuid(),
                                        'userId'     => $userId,
                                        'leadId'     => $leadRecord->id,
                                        'materialId' => $sizes['id'],
                                        'totalQty'   => $sizes['totalQty'],
                                        'msType' => $sizes['msType'],
                                        'msName'   => $sizes['msName'],
                                        'materialTypeId' => $materialType['id'],
                                        'isParent' => 0,
                                    ];
                                }

                            }
                        }
                }
            }

                $leadRecord->materialType = json_encode($mtArray) ?? "";
                $leadRecord->mtListView = substr_replace($mtListView ,"", -4)?? "";
                $leadRecord->mtListEdit = substr_replace($mtTitle ,"", -1) ?? "";
                $leadRecord->save();

        }

        return true;
    }



}
